@extends('layouts.app')

@section('content')
<div class="layout-wrapper layout-content-navbar {{ $background_class ?? 'bg-eoffice' }}">
    <div class="layout-container">
        <x-menu /> <!-- Load the menu component here -->
      <!-- Layout container -->
      <div class="layout-page ">
        <!-- Navbar -->

        <x-header />

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">



                        <!-- Total Profit -->
                         <div class="col-xl-4 col-md-4 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                <div class="badge p-2 bg-label-info mb-2 rounded"> <h4 class="mb-2 mt-1">{{ $total_stock ?? 0; }} </h4></div>
                                <h5 class="card-title mb-1 pt-2">Asset Stock</h5>
                                <small class="text-muted">Count of total assets</small>
                                    <div class="">
                                        <a href="{{ route('assets.all') }}" class="form-control btn btn-primary">Assets</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Sales -->
                         <div class="col-xl-2 col-md-4 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                <div class="badge p-2 bg-label-info mb-2 rounded"><h4 class="mb-2 mt-1">{{ $stock_in_hand ?? 0; }}</h4></div>
                                <h5 class="card-title mb-1 pt-2">Assets in Store</h5>
                                <small class="text-muted">Count of assets in store</small>
                                <div class="">
                                     <a href="{{ route('assets.register.index') }}" class="form-control btn btn-primary">Add Assets</a>
                                </div>
                                </div>
                            </div>
                        </div>

                         <div class="col-xl-2 col-md-4 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                <div class="badge p-2 bg-label-info mb-2 rounded"> <h4 class="mb-2 mt-1">{{ $stock_allocated ?? 0; }}</h4></div>
                                <h5 class="card-title mb-1 pt-2">Allocated Assets</h5>
                                <small class="text-muted">Count of allocated assets</small>
                                <div class="">
                                    <a href="{{ route('assets.allocation.index') }}" class="form-control btn btn-primary">Allocate Assets</a>
                                </div>
                                </div>
                            </div>
                        </div>

                         <div class="col-xl-2 col-md-4 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                <div class="badge p-2 bg-label-info mb-2 rounded"><h4 class="mb-2 mt-1">{{ $stock_repaired ?? 0; }}</h4></div>
                                <h5 class="card-title mb-1 pt-2">Reparing Assets</h5>
                                <small class="text-muted">Count of assets on repairing</small>
                                <div class="">
                                    <a href="{{ route('assets.repair-register.index') }}" class="form-control btn btn-primary">Add to repair</a>
                                </div>
                                </div>
                            </div>
                        </div>

                          <div class="col-xl-2 col-md-4 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                <div class="badge p-2 bg-label-info mb-2 rounded"><h4 class="mb-2 mt-1">{{ $stock_scraped ?? 0; }}</h4></div>
                                <h5 class="card-title mb-1 pt-2">Scrapped Assets</h5>
                                <small class="text-muted">Count of assets for scrap</small>
                                <div class="">
                                    <a href="{{ route('assets.scrap-register.index') }}" class="form-control btn btn-primary">Add to scrap</a>
                                </div>
                                </div>
                            </div>
                        </div>






                        <!-- Earning Reports Tabs-->
                        <div class="col-12 col-xl-12 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                <div class="card-title mb-0">
                                    <h5 class="mb-0">Reports</h5>
                                    <small class="text-muted">Asset reports</small>
                                </div>

                                </div>
                                <div class="card-body">
                                <ul class="nav nav-tabs widget-nav-tabs pb-3 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
                                    <li class="nav-item">
                                    <a href="{{ route('assets.reports.stock-report') }}"
                                        class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-warning">
                                        <div class="badge bg-label-secondary rounded p-2">
                                        <i class="ti ti-file ti-sm"></i>
                                        </div>
                                        <h6 class="tab-widget-title mb-0 mt-2">Stock Report</h6>
                                    </a>
                                    </li>
                                    <li class="nav-item">
                                    <a href="{{ route('assets.reports.allocated-items') }}"
                                        class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-warning">
                                        <div class="badge bg-label-secondary rounded p-2">
                                        <i class="ti ti-file ti-sm"></i>
                                        </div>
                                        <h6 class="tab-widget-title mb-0 mt-2">Allocation Report</h6>
                                    </a>
                                    </li>
                                    <li class="nav-item">
                                    <a href="{{ route('assets.reports.scrap-items') }}"
                                        class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-warning">
                                        <div class="badge bg-label-secondary rounded p-2">
                                        <i class="ti ti-file ti-sm"></i>
                                        </div>
                                        <h6 class="tab-widget-title mb-0 mt-2">Scrap Report</h6>
                                    </a>
                                    </li>
                                    <li class="nav-item">
                                    <a href="{{ route('assets.reports.repair-items') }}"
                                        class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-warning">
                                        <div class="badge bg-label-secondary rounded p-2">
                                        <i class="ti ti-file ti-sm"></i>
                                        </div>
                                        <h6 class="tab-widget-title mb-0 mt-2">Repair Report</h6>
                                    </a>
                                    </li>

                                </ul>
                                <div class="tab-content p-0 ms-0 ms-sm-2">
                                    <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                                    <div id="earningReportsTabsOrders"></div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
                                    <div id="earningReportsTabsSales"></div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
                                    <div id="earningReportsTabsProfit"></div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
                                    <div id="earningReportsTabsIncome"></div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>


                        <!-- Earning settings Tabs-->
                        <div class="col-12 col-xl-12 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                <div class="card-title mb-0">
                                    <h5 class="mb-0">Settings</h5>
                                    <small class="text-muted">Asset reports</small>
                                </div>

                                </div>
                                <div class="card-body">
                                <ul class="nav nav-tabs widget-nav-tabs pb-3 gap-4 mx-1 d-flex flex-nowrap" role="tablist">

                                    <li class="nav-item">
                                        <a href="{{ route('classification.index') }}"
                                            class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-info">
                                            <div class="badge bg-label-secondary rounded p-2">
                                            <i class="ti ti-settings ti-sm"></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Asset Classifications</h6>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('classification.index') }}"
                                            class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-info">
                                            <div class="badge bg-label-secondary rounded p-2">
                                            <i class="ti ti-settings ti-sm"></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Asset Categories</h6>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('assets.type.index') }}"
                                            class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-info">
                                            <div class="badge bg-label-secondary rounded p-2">
                                            <i class="ti ti-settings ti-sm"></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Asset Types</h6>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('assets.location.index') }}"
                                            class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-info">
                                            <div class="badge bg-label-secondary rounded p-2">
                                            <i class="ti ti-settings ti-sm"></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Asset Locations</h6>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('assets.itemmaster.index') }}"
                                            class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-info">
                                            <div class="badge bg-label-secondary rounded p-2">
                                            <i class="ti ti-settings ti-sm"></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Asset Item Master</h6>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('assets.vendors.index') }}"
                                            class="nav-link btn d-flex flex-column align-items-center justify-content-center bg-info">
                                            <div class="badge bg-label-secondary rounded p-2">
                                            <i class="ti ti-settings ti-sm"></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Vendors</h6>
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content p-0 ms-0 ms-sm-2">
                                    <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                                    <div id="earningReportsTabsOrders"></div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
                                    <div id="earningReportsTabsSales"></div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
                                    <div id="earningReportsTabsProfit"></div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
                                    <div id="earningReportsTabsIncome"></div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            <!-- / Content -->
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
@endsection

