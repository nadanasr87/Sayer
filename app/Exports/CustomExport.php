<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomExport implements   FromArray, WithHeadings
{
    protected $data;
    // protected $search;

    public function __construct($data) {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'finalStatus',
            'orderNumber',
            'orderDate',
            'deliveryTime',
            'authorityId',
            'categoryId',
            'regionId',
            'cityId',
            'coordinates',
            'recipientMobileNumber',
            'storetName',
            'storeLocation',
            'acceptanceDateTime',
            'idNumber',
            'executionTime',
            'paymentMethodId',
            'price',
            'priceWithoutDelivery',
            'deliveryPrice',
            'driverIncome',
            'cancelationReasonId',
        ];
    }
    public function array(): array
    {
        $data2=[]; 
        foreach ($this->data as $order) {
            $jsonData = json_decode($order->delivery_address, true);
            $coor = $jsonData['latitude'].','.$jsonData['longitude'];
            $data3=[
                "Executed",
                $order->id,
                $order->created_at,
                $order->delivered,
                "NV25GlPuOnQ=",
                "NV25GlPuOnQ=",
                "NV25GlPuOnQ=",
                "NV25GlP",
                $coor,
                $jsonData["contact_person_number"],
                "noStore",
                "noLoc",
                $order->picked_up,
                "idNumber",
                $order->picked_up,
                "paymentMethodId",
                $order->order_amount,
                '0',
                $order->order_amount,
                $order->delivery_charge,
            ];
            $data2[] = $data3; 
        }
        // info($data2);
        return $data2;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
}
