<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

class InvoiceVoucherRefIdUtil
{
    public function generateCategoryCode()
    {
        $id = 1;
        $lastCategory = DB::table('categories')->where('parent_category_id')->orderBy('id', 'desc')->first(['id']);

        if ($lastCategory) {

            $id = ++$lastCategory->id;
        }

        return str_pad($id, 3, '0', STR_PAD_LEFT);
    }

    public function getLastId($table)
    {
        $id = 1;
        $lastEntry = DB::table($table)->orderBy('id', 'desc')->first(['id']);

        if ($lastEntry) {

            $id = ++$lastEntry->id;
        }

        return $id;
    }

    public function generateDirectSaleInvoice()
    {
        $id = 1;
        $count = DB::table('sales')->orderBy('id', 'desc')->where('status', 1)->count();

        if ($count > 0) {

            $id = ++$count;
        }

        return auth()->user()->user_id.'-'.date('ymd').'-'.str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public function generateInvoiceIdForDoToInvoice()
    {
        $id = 1;
        $count = DB::table('sales')->orderBy('id', 'desc')->where('status', 1)->count();

        if ($count > 0) {

            $id = ++$count;
        }

        return auth()->user()->user_id.'-'.date('ymd').'-'.str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public function generateDoId()
    {
        $id = 1;
        $count = DB::table('sales')->orderBy('id', 'desc')->where('do_status', 1)->count();

        if ($count > 0) {

            $id = ++$count;
        }

        return auth()->user()->user_id.'-'.date('ymd').'-'.str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public function generateOrderId($userId = null)
    {
        // if ($userId) {

        //     $user = DB::table('users')->where('id', $userId)->select('user_id')->first();
        // }

        // $user_id = $user ? $user->user_id : auth()->user()->user_id;

        $id = 1;
        $count = DB::table('sales')->orderBy('id', 'desc')->where('order_status', 1)->count();

        if ($count > 0) {

            $id = ++$count;
        }

        return auth()->user()->user_id.'-'.date('ymd').'-'.str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public function generateQuotationId($userId = null)
    {
        // if ($userId) {

        //     $user = DB::table('users')->where('id', $userId)->select('user_id')->first();
        // }

        // $user_id = $user ? $user->user_id : auth()->user()->user_id;

        $id = 1;
        $count = DB::table('sales')->orderBy('id', 'desc')->where('quotation_id', 1)->count();

        if ($count > 0) {

            $id = ++$count;
        }

        return auth()->user()->user_id.'-'.date('ymd').'-'.str_pad($id, 4, '0', STR_PAD_LEFT);
    }
}
