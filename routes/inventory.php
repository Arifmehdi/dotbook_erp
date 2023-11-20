<?php

use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BranchReceiveStockController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BulkVariantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DailyStockController;
use App\Http\Controllers\ImportPriceGroupProductController;
use App\Http\Controllers\InventoryDashboardController;
use App\Http\Controllers\InventorySettingController;
use App\Http\Controllers\OpeningStockController;
use App\Http\Controllers\PriceGroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\Report\DailyStockItemReportController;
use App\Http\Controllers\Report\StockAdjustedProductReportController;
use App\Http\Controllers\Report\StockAdjustmentReportController;
use App\Http\Controllers\Report\StockInOutReportController;
use App\Http\Controllers\Report\StockReportController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TransferToBranchController;
use App\Http\Controllers\TransferToWarehouseController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseReceiveStockController;
use App\Http\Controllers\WarrantyController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'inventories'], function () {

    Route::group(['prefix' => 'product'], function () {
        // Branch route group
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('product.categories.index');
            Route::get('create', [CategoryController::class, 'create'])->name('product.categories.create');
            Route::post('store', [CategoryController::class, 'store'])->name('product.categories.store');
            Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('product.categories.edit');
            Route::post('update/{id}', [CategoryController::class, 'update'])->name('product.categories.update');
            Route::delete('delete/{id}', [CategoryController::class, 'delete'])->name('product.categories.delete');
        });

        Route::group(['prefix' => 'sub-categories'], function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('product.subcategories.index');
            Route::get('create/{fixedParentCategoryId?}', [SubCategoryController::class, 'create'])->name('product.subcategories.create');
            Route::post('store', [SubCategoryController::class, 'store'])->name('product.subcategories.store');
            Route::get('edit/{id}', [SubCategoryController::class, 'edit'])->name('product.subcategories.edit');
            Route::post('update/{id}', [SubCategoryController::class, 'update'])->name('product.subcategories.update');
            Route::delete('delete/{categoryId}', [SubCategoryController::class, 'delete'])->name('product.subcategories.delete');
        });

        // Brand route group
        Route::group(['prefix' => 'brands'], function () {
            Route::get('/', [BrandController::class, 'index'])->name('product.brands.index');
            Route::get('create', [BrandController::class, 'create'])->name('product.brands.create');
            Route::post('store', [BrandController::class, 'store'])->name('product.brands.store');
            Route::get('edit/{id}', [BrandController::class, 'edit'])->name('product.brands.edit');
            Route::post('update/{id}', [BrandController::class, 'update'])->name('product.brands.update');
            Route::delete('delete/{id}', [BrandController::class, 'delete'])->name('product.brands.delete');
        });

        // Products route group
        Route::group(['prefix' => 'products'], function () {

            Route::get('all', [ProductController::class, 'allProduct'])->name('products.all.product');
            Route::get('view/{productId}', [ProductController::class, 'view'])->name('products.view');
            Route::get('get/all/product', [ProductController::class, 'getAllProduct'])->name('products.get.all.product');
            Route::get('add', [ProductController::class, 'create'])->name('products.add.view');
            Route::get('get/form/part/{type}', [ProductController::class, 'getFormPart'])->name('products.get.form.part');
            Route::post('store', [ProductController::class, 'store'])->name('products.add.store');
            Route::get('edit/{productId}', [ProductController::class, 'edit'])->name('products.edit');
            Route::get('product/variants/{productId}', [ProductController::class, 'getProductVariants'])->name('products.get.product.variants');
            Route::get('combo/product/{productId}', [ProductController::class, 'getComboProducts'])->name('products.get.combo.products');
            Route::post('update/{productId}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('delete/{productId}', [ProductController::class, 'delete'])->name('products.delete');
            Route::delete('multiple/delete', [ProductController::class, 'multipleDelete'])->name('products.multiple.delete');
            Route::get('all/form/variant', [ProductController::class, 'getAllFormVariants'])->name('products.add.get.all.from.variant');
            Route::get('search/product/{productCode}', [ProductController::class, 'searchProduct']);
            Route::get('get/product/stock/{productId}', [ProductController::class, 'getProductStock']);
            Route::get('change/status/{productId}', [ProductController::class, 'changeStatus'])->name('products.change.status');
            Route::get('add/price/groups/{productId}/{type}', [ProductController::class, 'addPriceGroup'])->name('products.add.price.groups');
            Route::post('save/price/groups', [ProductController::class, 'savePriceGroup'])->name('products.save.price.groups');
            Route::post('add/category', [ProductController::class, 'addCategory'])->name('products.add.category');
            Route::get('productstatuschange', [ProductController::class, 'productstatus'])->name('product.status.change');

            Route::group(['prefix' => 'import/price/group/products'], function () {

                Route::get('export', [ImportPriceGroupProductController::class, 'export'])->name('products.export.price.group.products');
            });

            Route::controller(OpeningStockController::class)->prefix('opening/stock')->group(function () {
                Route::get('create/or/edit/{productId}', 'createOrEdit')->name('products.opening.stock.create.or.edit');
                Route::post('save/add/or/update', 'saveAddOrUpdate')->name('products.opening.stock.save.add.or.update');
            });
        });

        // Selling price group route group
        Route::group(['prefix' => 'selling/price/groups'], function () {

            Route::get('/', [PriceGroupController::class, 'index'])->name('product.selling.price.groups.index');
            Route::get('create', [PriceGroupController::class, 'create'])->name('product.selling.price.groups.create');
            Route::post('store', [PriceGroupController::class, 'store'])->name('product.selling.price.groups.store');
            Route::get('edit/{id}', [PriceGroupController::class, 'edit'])->name('product.selling.price.groups.edit');
            Route::post('update/{id}', [PriceGroupController::class, 'update'])->name('product.selling.price.groups.update');
            Route::delete('delete/{id}', [PriceGroupController::class, 'delete'])->name('product.selling.price.groups.delete');
            Route::get('change/status/{id}', [PriceGroupController::class, 'changeStatus'])->name('product.selling.price.groups.change.status');
            Route::get('changestatus', [PriceGroupController::class, 'changeSta'])->name('groups.change.status');
        });

        // Variants route group
        Route::group(['prefix' => 'variants'], function () {

            Route::get('/', [BulkVariantController::class, 'index'])->name('product.variants.index');
            Route::get('all', [BulkVariantController::class, 'getAllVariant'])->name('product.variants.all.variant');
            Route::post('store', [BulkVariantController::class, 'store'])->name('product.variants.store');
            Route::post('update', [BulkVariantController::class, 'update'])->name('product.variants.update');
            Route::delete('delete/{id}', [BulkVariantController::class, 'delete'])->name('product.variants.delete');
        });

        // Barcode route group
        Route::group(['prefix' => 'barcode'], function () {

            Route::get('/', [BarcodeController::class, 'index'])->name('barcode.index');
            Route::post('preview', [BarcodeController::class, 'preview'])->name('barcode.preview');
            Route::get('supplier/products', [BarcodeController::class, 'supplierProduct'])->name('barcode.supplier.get.products');
            Route::post('multiple/generate/completed', [BarcodeController::class, 'multipleGenerateCompleted'])->name('barcode.multiple.generate.completed');
            Route::get('search/product/{searchKeyword}', [BarcodeController::class, 'searchProduct']);
            Route::get('get/selected/product/{productId}', [BarcodeController::class, 'getSelectedProduct']);
            Route::get('get/selected/product/variant/{productId}/{variantId}', [BarcodeController::class, 'getSelectedProductVariant']);
            Route::get('generate/product/barcode/{productId}', [BarcodeController::class, 'genrateProductBarcode'])->name('products.generate.product.barcode');
            Route::get('get/spacific/supplier/product/{productId}', [BarcodeController::class, 'getSpacificSupplierProduct'])->name('barcode.get.spacific.supplier.product');

            // Generate bar-codes on purchase.
            Route::get('purchase/products/{purchaseId}', [BarcodeController::class, 'onPurchaseBarcode'])->name('barcode.on.purchase.barcode');
            Route::get('get/purchase/products/{purchaseId}', [BarcodeController::class, 'getPurchaseProduct'])->name('barcode.get.purchase.products');
        });

        // Import product route group
        Route::group(['prefix' => 'imports'], function () {
            Route::get('create', [ProductImportController::class, 'create'])->name('product.import.create');
            Route::post('store', [ProductImportController::class, 'store'])->name('product.import.store');
        });

        Route::controller(UnitController::class)->prefix('units')->group(function () {
            Route::get('/', 'index')->name('products.units.index');
            Route::get('create/{isAllowedMultipleUnit?}', 'create')->name('products.units.create');
            Route::post('store', 'store')->name('products.units.store');
            Route::get('edit/{id}', 'edit')->name('products.units.edit');
            Route::post('update/{id}', 'update')->name('products.units.update');
            Route::delete('delete/{id}', 'delete')->name('products.units.delete');
        });

        // Warranty route group
        Route::controller(WarrantyController::class)->prefix('warranties')->group(function () {
            Route::get('/', 'index')->name('product.warranties.index');
            Route::post('store', 'store')->name('product.warranties.store');
            Route::get('create', 'create')->name('product.warranties.create');
            Route::get('edit/{id}', 'edit')->name('product.warranties.edit');
            Route::post('update/{id}', 'update')->name('product.warranties.update');
            Route::delete('delete/{id}', 'delete')->name('product.warranties.delete');
        });
    });

    Route::group(['prefix' => 'daily/stock'], function () {

        Route::get('/', [DailyStockController::class, 'index'])->name('daily.stock.index');
        Route::get('show/{dailyStockId}', [DailyStockController::class, 'show'])->name('daily.stock.show');
        Route::get('create', [DailyStockController::class, 'create'])->name('daily.stock.create');
        Route::post('store', [DailyStockController::class, 'store'])->name('daily.stock.store');
        Route::get('edit/{dailyStockId}', [DailyStockController::class, 'edit'])->name('daily.stock.edit');
        Route::post('update/{dailyStockId}', [DailyStockController::class, 'update'])->name('daily.stock.update');
        Route::delete('delete/{dailyStockId}', [DailyStockController::class, 'delete'])->name('daily.stock.delete');
        Route::get('search/product/{keyword}', [DailyStockController::class, 'searchProduct'])->name('daily.stock.search');
    });

    Route::group(['prefix' => 'stock/adjustments'], function () {

        Route::get('/', [StockAdjustmentController::class, 'index'])->name('stock.adjustments.index');
        Route::get('show/{adjustmentId}', [StockAdjustmentController::class, 'show'])->name('stock.adjustments.show');
        Route::get('create', [StockAdjustmentController::class, 'create'])->name('stock.adjustments.create');
        Route::post('store', [StockAdjustmentController::class, 'store'])->name('stock.adjustments.store');
        Route::delete('delete/{adjustmentId}', [StockAdjustmentController::class, 'delete'])->name('stock.adjustments.delete');
    });

    Route::group(['prefix' => 'transfer/stocks/wh/to/branch', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('/', [TransferToBranchController::class, 'index'])->name('transfer.stock.to.branch.index');
        Route::get('show/{transferId}', [TransferToBranchController::class, 'show'])->name('transfer.stock.to.branch.show');
        Route::get('transfer/products/{transferId}', [TransferToBranchController::class, 'transferProduct']);
        Route::get('all/transfer/', [TransferToBranchController::class, 'allTransfer'])->name('transfer.stock.to.branch.all.transfer');
        Route::get('create', [TransferToBranchController::class, 'create'])->name('transfer.stock.to.branch.create');
        Route::post('store', [TransferToBranchController::class, 'store'])->name('transfer.stock.to.branch.store');
        Route::get('get/all/warehouse', [TransferToBranchController::class, 'getAllWarehouse'])->name('transfer.stock.to.branch.all.warehouse');
        Route::get('edit/{transferId}', [TransferToBranchController::class, 'edit'])->name('transfer.stock.to.branch.edit');
        Route::get('get/editable/transfer/{transferId}', [TransferToBranchController::class, 'editableTransfer'])->name('transfer.stock.to.branch.editable.transfer');
        Route::post('update/{transferId}', [TransferToBranchController::class, 'update'])->name('transfer.stock.to.branch.update');
        Route::delete('delete/{transferId}', [TransferToBranchController::class, 'delete'])->name('transfer.stock.to.branch.delete');

        Route::get('search/product/{product_code}/{warehouse_id}', [TransferToBranchController::class, 'productSearch'])->name('transfer.to.branch.search.item');

        Route::get('check/warehouse/single/product/{product_id}/{warehouse_id}', [TransferToBranchController::class, 'checkWarehouseSingleProduct'])->name('transfer.to.branch.check.warehouse.single.item');

        Route::get('check/warehouse/variant/qty/{product_id}/{variant_id}/{warehouse_id}', [TransferToBranchController::class, 'checkWarehouseProductVariant'])->name('transfer.to.branch.check.warehouse.variant.item');

        // Receive stock from branch **route group**
        Route::group(['prefix' => 'receive'], function () {
            Route::get('/', [BranchReceiveStockController::class, 'index'])->name('transfer.stocks.to.warehouse.receive.stock.index');
            Route::get('show/{sendStockId}', [BranchReceiveStockController::class, 'show'])->name('transfer.stocks.to.warehouse.receive.stock.show');
            Route::get('all/send/stocks', [BranchReceiveStockController::class, 'allSendStock'])->name('transfer.stocks.to.warehouse.receive.stock.all.send.stocks');
            Route::get('process/{sendStockId}', [BranchReceiveStockController::class, 'receiveProcessView'])->name('transfer.stocks.to.warehouse.receive.stock.process.view');
            Route::get('receivable/stock/{sendStockId}', [BranchReceiveStockController::class, 'receivableStock'])->name('transfer.stocks.to.warehouse.receive.stock.get.receivable.stock');
            Route::post('process/save/{sendStockId}', [BranchReceiveStockController::class, 'receiveProcessSave'])->name('transfer.stocks.to.warehouse.receive.stock.process.save');
            Route::post('mail/{sendStockId}', [BranchReceiveStockController::class, 'receiveMail'])->name('transfer.stocks.to.warehouse.receive.stock.mail');
        });
    });

    //Transfer stock to warehouse all route
    Route::group(['prefix' => 'transfer/stocks/branch/to/wh'], function () {

        Route::get('/', [TransferToWarehouseController::class, 'index'])->name('transfer.stock.to.warehouse.index');
        Route::get('show/{id}', [TransferToWarehouseController::class, 'show'])->name('transfer.stock.to.warehouse.show');
        Route::get('create', [TransferToWarehouseController::class, 'create'])->name('transfer.stock.to.warehouse.create');
        Route::post('store', [TransferToWarehouseController::class, 'store'])->name('transfer.stock.to.warehouse.store');
        Route::get('get/all/warehouse', [TransferToWarehouseController::class, 'getAllWarehouse'])->name('transfer.stock.to.warehouse.all.warehouse');
        Route::get('edit/{transferId}', [TransferToWarehouseController::class, 'edit'])->name('transfer.stock.to.warehouse.edit');
        Route::get('get/editable/transfer/{transferId}', [TransferToWarehouseController::class, 'editableTransfer'])->name('transfer.stock.to.warehouse.editable.transfer');

        Route::post('update/{transferId}', [TransferToWarehouseController::class, 'update'])->name('transfer.stock.to.warehouse.update');
        Route::delete('delete/{transferId}', [TransferToWarehouseController::class, 'delete'])->name('transfer.stock.to.warehouse.delete');
        Route::get('search/product/{product_code}', [TransferToWarehouseController::class, 'productSearch'])->name('transfer.to.warehouse.search.item');
        Route::get('check/single/product/stock/{product_id}', [TransferToWarehouseController::class, 'checkBranchSingleProduct'])->name('transfer.to.warehouse.check.single.item.stock');
        Route::get('check/branch/variant/qty/{product_id}/{variant_id}', [TransferToWarehouseController::class, 'checkBranchProductVariant'])->name('transfer.to.warehouse.check.variant.item.stock');

        // Receive stock from warehouse **route group**
        Route::group(['prefix' => 'receive'], function () {
            Route::get('/', [WarehouseReceiveStockController::class, 'index'])->name('transfer.stocks.to.branch.receive.stock.index');
            Route::get('show/{sendStockId}', [WarehouseReceiveStockController::class, 'show'])->name('transfer.stocks.to.branch.receive.stock.show');
            Route::get('process/{sendStockId}', [WarehouseReceiveStockController::class, 'receiveProcessView'])->name('transfer.stocks.to.branch.receive.stock.process.view');
            Route::get('receivable/stock/{sendStockId}', [WarehouseReceiveStockController::class, 'receivableStock'])->name('transfer.stocks.to.branch.receive.stock.get.receivable.stock');
            Route::post('process/save/{sendStockId}', [WarehouseReceiveStockController::class, 'receiveProcessSave'])->name('transfer.stocks.to.branch.receive.stock.process.save');
        });
    });

    Route::controller(InventoryDashboardController::class)->prefix('dashboard')->group(function () {

        Route::get('/', 'index')->name('inventories.dashboard.index');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'stock'], function () {
            Route::get('/', [StockReportController::class, 'index'])->name('reports.stock.index');
            Route::get('print/branch/stocks', [StockReportController::class, 'printBranchStock'])->name('reports.stock.print.branch.stock');
            Route::get('print/warehouse/stocks', [StockReportController::class, 'printWarehouseStock'])->name('reports.stock.print.warehouse.stock');

            Route::get('print/warehouse/stock/value', [StockReportController::class, 'printWarehouseStockValue'])->name('reports.stock.print.warehouse.stock.value');

            Route::get('warehouse/stock', [StockReportController::class, 'warehouseStock'])->name('reports.stock.warehouse.stock');
            Route::get('all/parent/categories', [StockReportController::class, 'allParentCategories'])->name('reports.stock.all.parent.categories');
        });

        Route::group(['prefix' => 'stock/in/out'], function () {
            Route::get('/', [StockInOutReportController::class, 'index'])->name('reports.stock.in.out.index');
            Route::get('print', [StockInOutReportController::class, 'print'])->name('reports.stock.in.out.print');
        });

        Route::group(['prefix' => 'reports/stock/adjustments'], function () {

            Route::get('/', [StockAdjustmentReportController::class, 'index'])->name('reports.stock.adjustments.index');
            Route::get('all/adjustments', [StockAdjustmentReportController::class, 'allAdjustments'])->name('reports.stock.adjustments.all');
            Route::get('print', [StockAdjustmentReportController::class, 'print'])->name('reports.stock.adjustments.print');
        });

        Route::group(['prefix' => 'reports/stock/adjusted/products'], function () {

            Route::get('/', [StockAdjustedProductReportController::class, 'index'])->name('reports.stock.adjusted.index');
            Route::get('print', [StockAdjustedProductReportController::class, 'print'])->name('reports.stock.adjusted.print');
        });

        Route::group(['prefix' => 'reports/daily/stock/item/report'], function () {

            Route::get('/', [DailyStockItemReportController::class, 'index'])->name('reports.daily.stock.index');
            Route::get('print', [DailyStockItemReportController::class, 'print'])->name('reports.daily.stock.print');
        });
    });

    Route::controller(InventorySettingController::class)->prefix('settings')->group(function () {

        Route::get('/', 'index')->name('inventories.settings.index');
        Route::post('item/settings/update', 'itemSettingsUpdate')->name('inventories.settings.item.settings.update');
    });
});
