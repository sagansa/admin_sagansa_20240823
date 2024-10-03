<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(1)
            ->create([
                'email' => 'admin@admin.com',
                'password' => \Hash::make('admin'),
            ]);

        $this->call(AccountCashlessSeeder::class);
        $this->call(AdminCashlessSeeder::class);
        $this->call(AdvancePurchaseSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(CashAdvanceSeeder::class);
        $this->call(CashlessSeeder::class);
        $this->call(CashlessProviderSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(ClosingCourierSeeder::class);
        $this->call(ClosingStoreSeeder::class);
        $this->call(ContractEmployeeSeeder::class);
        $this->call(ContractLocationSeeder::class);
        $this->call(DailySalarySeeder::class);
        $this->call(DeliveryAddressSeeder::class);
        $this->call(DeliveryServiceSeeder::class);
        $this->call(DetailAdvancePurchaseSeeder::class);
        $this->call(DetailInvoiceSeeder::class);
        $this->call(DetailRequestSeeder::class);
        $this->call(DetailSalesOrderSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(EmployeeStatusSeeder::class);
        $this->call(FuelServiceSeeder::class);
        $this->call(HygieneSeeder::class);
        $this->call(HygieneOfRoomSeeder::class);
        $this->call(InvoicePurchaseSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(MaterialGroupSeeder::class);
        $this->call(MonthlySalarySeeder::class);
        $this->call(MovementAssetSeeder::class);
        $this->call(MovementAssetAuditSeeder::class);
        $this->call(MovementAssetResultSeeder::class);
        $this->call(OnlineCategorySeeder::class);
        $this->call(OnlineShopProviderSeeder::class);
        $this->call(PaymentReceiptSeeder::class);
        $this->call(PaymentTypeSeeder::class);
        $this->call(PermitEmployeeSeeder::class);
        $this->call(PostalCodeSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductionSeeder::class);
        $this->call(ProductionMainFromSeeder::class);
        $this->call(ProductionSupportFromSeeder::class);
        $this->call(ProductionToSeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(ReadinessSeeder::class);
        $this->call(RemainingStockSeeder::class);
        $this->call(RequestPurchaseSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(SalesOrderSeeder::class);
        $this->call(SalesOrderDirectSeeder::class);
        $this->call(SalesOrderEmployeeSeeder::class);
        $this->call(SalesOrderOnlineSeeder::class);
        $this->call(SelfConsumptionSeeder::class);
        $this->call(ShiftStoreSeeder::class);
        $this->call(StoreSeeder::class);
        $this->call(StoreAssetSeeder::class);
        $this->call(StoreCashlessSeeder::class);
        $this->call(SubdistrictSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(TransferStockSeeder::class);
        $this->call(TransferToAccountSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(UtilitySeeder::class);
        $this->call(UtilityBillSeeder::class);
        $this->call(UtilityProviderSeeder::class);
        $this->call(UtilityUsageSeeder::class);
        $this->call(VehicleSeeder::class);
        $this->call(VehicleCertificateSeeder::class);
        $this->call(VehicleTaxSeeder::class);
        $this->call(WorkingExperienceSeeder::class);
        $this->call(ProductRemainingStockSeeder::class);
        $this->call(DailySalaryPaymentReceiptSeeder::class);
        $this->call(FuelServicePaymentReceiptSeeder::class);
        $this->call(InvoicePurchasePaymentReceiptSeeder::class);
        $this->call(UserCashlessSeeder::class);
        $this->call(StorageStockSeeder::class);
        $this->call(ProductStorageStockSeeder::class);
        $this->call(ProductSelfConsumptionSeeder::class);
        $this->call(ProductTransferStockSeeder::class);
        $this->call(PresenceSeeder::class);
        $this->call(StockCardSeeder::class);
        $this->call(DetailStockCardSeeder::class);
        $this->call(TransferCardSeeder::class);
        $this->call(DetailTransferCardSeeder::class);
        $this->call(TransferCardStorageSeeder::class);
        $this->call(TransferCardStoreSeeder::class);
        $this->call(RemainingStorageSeeder::class);
        $this->call(EmployeeConsumptionSeeder::class);
        $this->call(StoreConsumptionSeeder::class);
        $this->call(RemainingStoreSeeder::class);
    }
}
