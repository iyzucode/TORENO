<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderItemsSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Detail Item';
    }

    public function collection()
    {
        $orders = Order::with(['items'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->get();

        // Flatten order items with their parent order info
        $items = collect();
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $item->_order_code = $order->order_code;
                $item->_order_time = $order->created_at;
                $items->push($item);
            }
        }

        return $items;
    }

    public function headings(): array
    {
        return [
            'Kode Pesanan',
            'Waktu Pesanan',
            'Nama Menu',
            'Jumlah',
            'Harga Satuan',
            'Subtotal Item',
            'Catatan Item',
        ];
    }

    public function map($item): array
    {
        return [
            $item->_order_code,
            Carbon::parse($item->_order_time)->format('d/m/Y H:i'),
            $item->menu_name ?: 'Menu Tidak Diketahui',
            $item->quantity,
            $item->price,
            $item->subtotal,
            $item->notes ?: '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D4E8D4'],
                ],
            ],
        ];
    }
}
