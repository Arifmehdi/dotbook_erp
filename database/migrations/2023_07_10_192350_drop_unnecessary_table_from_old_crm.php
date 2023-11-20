<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // $tables = [
        //     'appointments',
        //     'asset_service_provides',
        //     'asset_service_engineers',
        //     'business_leads',
        //     'crm_settings',
        //     'estimates',
        //     'followups',
        //     'followup_categories',
        //     'individual_leads',
        //     'leads_contacts',
        //     'life_stages',
        //     'proposal_templates',
        //     'sources',
        // ];

        // foreach ($tables as $table) {
        //     Schema::connection('mysql')->dropIfExists($table);
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not needed.
    }
};
