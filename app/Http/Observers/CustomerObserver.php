<?php

namespace App\Observers;

use App\Models\Customers;

class CustomerObserver
{
    /**
     * Handle the Events "created" event.
     *
     * @param Customers $customer
     * @return void
     */
    public function created(Customers $customer)
    {
        //
    }

    /**
     * Handle the Events "updated" event.
     *
     * @param Customers $customer
     * @return void
     */
    public function updated(Customers $customer)
    {
        //
    }

    /**
     * Handle the Events "deleted" event.
     *
     * @param Customers $customer
     * @return void
     */
    public function deleted(Customers $customer)
    {
        $customer->Calendar()->delete();
    }

    /**
     * Handle the Events "restored" event.
     *
     * @param Customers $customer
     * @return void
     */
    public function restored(Customers $customer)
    {
        //
    }

    /**
     * Handle the Events "force deleted" event.
     *
     * @param Customers $customer
     * @return void
     */
    public function forceDeleted(Customers $customer)
    {
        //
    }
}
