<?php $__env->startSection('content'); ?>
 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <?php if (isset($component)) { $__componentOriginal5893a4ae82cbde8a6e1ba16203c33ac0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5893a4ae82cbde8a6e1ba16203c33ac0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5893a4ae82cbde8a6e1ba16203c33ac0)): ?>
<?php $attributes = $__attributesOriginal5893a4ae82cbde8a6e1ba16203c33ac0; ?>
<?php unset($__attributesOriginal5893a4ae82cbde8a6e1ba16203c33ac0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5893a4ae82cbde8a6e1ba16203c33ac0)): ?>
<?php $component = $__componentOriginal5893a4ae82cbde8a6e1ba16203c33ac0; ?>
<?php unset($__componentOriginal5893a4ae82cbde8a6e1ba16203c33ac0); ?>
<?php endif; ?> <!-- Load the menu component here -->
      
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $attributes = $__attributesOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__attributesOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $component = $__componentOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__componentOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?> 
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">Permissions List</h4>
 
            <!-- Permission Table -->
            <div class="card">
              <div class="card-datatable table-responsive">
                <table class="datatables-permissions table border-top">
                  <thead>
                    <tr>
                      <th></th>
                      <th></th>
                      <th>Name</th>
                      <th>Assigned To</th>
                      <th>Created Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <!--/ Permission Table -->

            <!-- Modal -->
            <!-- Add Permission Modal -->
            <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                  <button
                    type="button"
                    class="btn-close btn-pinned"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                  <div class="modal-body">
                    <div class="text-center mb-4">
                      <h3 class="mb-2">Add New Permission</h3>
                      <p class="text-muted">Permissions you may use and assign to your users.</p>
                    </div>
                    <form id="addPermissionForm" method="post" action="<?php echo e(route('permissions.store')); ?>" class="row" onsubmit="return false">
                      <?php echo csrf_field(); ?>
                      <div class="col-12 mb-3">
                        <label class="form-label" for="name">Permission Name</label>
                        <input
                          type="text"
                          id="modalPermissionName"
                          name="name"
                          class="form-control"
                          placeholder="Permission Name"
                          autofocus />

                        <input type="hidden" value="web" name="guard_name">
                      </div>
                      <div class="col-12 mb-2">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="corePermission" />
                          <label class="form-check-label" for="corePermission"> Set as core permission </label>
                        </div>
                      </div>
                      <div class="col-12 text-center demo-vertical-spacing">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Create Permission</button>
                        <button
                          type="reset"
                          class="btn btn-label-secondary"
                          data-bs-dismiss="modal"
                          aria-label="Close">
                          Discard
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!--/ Add Permission Modal -->

            <!-- Edit Permission Modal -->
            <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                  <button
                    type="button"
                    class="btn-close btn-pinned"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                  <div class="modal-body">
                    <div class="text-center mb-4">
                      <h3 class="mb-2">Edit Permission</h3>
                      <p class="text-muted">Edit permission as per your requirements.</p>
                    </div>
                    <div class="alert alert-warning" role="alert">
                      <h6 class="alert-heading mb-2">Warning</h6>
                      <p class="mb-0">
                        By editing the permission name, you might break the system permissions functionality. Please
                        ensure you're absolutely certain before proceeding.
                      </p>
                    </div>
                    <form id="editPermissionForm" class="row" onsubmit="return false">
                      <div class="col-sm-9">
                        <label class="form-label" for="editPermissionName">Permission Name</label>
                        <input
                          type="text"
                          id="editPermissionName"
                          name="editPermissionName"
                          class="form-control"
                          placeholder="Permission Name"
                          tabindex="-1" />
                      </div>
                      <div class="col-sm-3 mb-3">
                        <label class="form-label invisible d-none d-sm-inline-block">Button</label>
                        <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
                      </div>
                      <div class="col-12">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="editCorePermission" />
                          <label class="form-check-label" for="editCorePermission"> Set as core permission </label>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!--/ Edit Permission Modal -->

            <!-- /Modal -->
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?> 
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div> 
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/allianze/Projects/eoffice-2025/eoffice/resources/views/permissions/index.blade.php ENDPATH**/ ?>