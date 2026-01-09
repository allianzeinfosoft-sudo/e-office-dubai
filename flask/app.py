from flask import Flask, request, jsonify
import poplib
from email import message_from_bytes
from email.header import decode_header
import base64

app = Flask(__name__)

def decode_header_value(value):
    if not value:
        return ""
    parts = decode_header(value)
    decoded = ""
    for part, enc in parts:
        if isinstance(part, bytes):
            decoded += part.decode(enc or "utf-8", errors="ignore")
        else:
            decoded += part
    return decoded

def extract_attachments(msg):
    attachments = []
    for part in msg.walk():
        filename = part.get_filename()
        if filename:
            decoded_name = decode_header_value(filename)
            file_data = part.get_payload(decode=True)
            if file_data:
                attachments.append({
                    "filename": decoded_name,
                    "content": base64.b64encode(file_data).decode("utf-8"),
                    "content_type": part.get_content_type()
                })
    return attachments


@app.route('/fetch-pop3-mails', methods=['POST'])
def fetch_pop3_mails():
    data = request.json

    host = data.get("host")
    port = int(data.get("port"))
    username = data.get("username")
    password = data.get("password")
    use_ssl = data.get("ssl", False)

    try:
        # Connect POP3
        if use_ssl:
            mailbox = poplib.POP3_SSL(host, port)
        else:
            mailbox = poplib.POP3(host, port)

        mailbox.user(username)
        mailbox.pass_(password)

        email_count = len(mailbox.list()[1])
        emails = []

        for i in range(email_count, 0, -1):  # latest first
            response, lines, octets = mailbox.retr(i)
            raw_email = b"\r\n".join(lines)
            msg = message_from_bytes(raw_email)

            # Basic details
            subject = decode_header_value(msg.get("Subject"))
            sender = decode_header_value(msg.get("From"))
            date = msg.get("Date")
            message_id = msg.get("Message-ID") or ""
            
            if not message_id:
               message_id = str(hash(raw_email))

            # Extract body
            body_plain = None
            body_html = None

            if msg.is_multipart():
                for part in msg.walk():
                    content_type = part.get_content_type()
                    payload = part.get_payload(decode=True)
                    if not payload:
                        continue

                    if content_type == "text/plain":
                        body_plain = payload.decode("utf-8", errors="ignore")

                    if content_type == "text/html":
                        body_html = payload.decode("utf-8", errors="ignore")
            else:
                body_plain = msg.get_payload(decode=True).decode("utf-8", errors="ignore")

            # Extract attachments
            attachments = extract_attachments(msg)

            # Add to list
            emails.append({
                "subject": subject,
                "from": sender,
                "date": date,
                "message_id": message_id,
                "body_plain": body_plain,
                "body_html": body_html,
                "attachments": attachments,
                "headers": dict(msg.items())
            })

        mailbox.quit()

        return jsonify({"status": True, "emails": emails})

    except Exception as e:
        return jsonify({"status": False, "message": str(e)})


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5002)
