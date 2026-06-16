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

class OrdersSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        return 'Pesanan';
    }

    public function collection()
    {
        return Order::with(['table'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Waktu',
            'Kode Pesanan',
            'Nomor Meja',
            'Nama Pemesan',
            'No. HP',
            'Metode Pembayaran',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Biaya Layanan',
            'Total Pembayaran',
            'Catatan',
        ];
    }

    public function map($order): array
    {
        return [
            Carbon::parse($order->created_at)->format('d/m/Y H:i'),
            $order->order_code,
            $order->table ? $order->table->table_number : '-',
            $order->customer_name ?: '-',
            $order->customer_phone ?: '-',
            strtoupper($order->payment_method),
            $order->subtotal ?? $order->total_amount,
            $order->discount_amount ?? 0,
            $order->tax_amount ?? 0,
            $order->service_charge_amount ?? 0,
            $order->total_amount,
            $order->notes ?: '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8D5C4'],
                ],
            ],
        ];
    }
}
