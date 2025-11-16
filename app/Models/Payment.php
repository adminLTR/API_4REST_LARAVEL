<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * Estados posibles de un pago
     */
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'transaction_id',
        'response_data',
        'error_message',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'response_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener el pedido asociado a este pago.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Verificar si el pago fue exitoso.
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * Verificar si el pago falló.
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Marcar el pago como exitoso.
     */
    public function markAsSuccess(string $transactionId, ?array $responseData = null): void
    {
        $this->update([
            'status' => self::STATUS_SUCCESS,
            'transaction_id' => $transactionId,
            'response_data' => $responseData,
        ]);
    }

    /**
     * Marcar el pago como fallido.
     */
    public function markAsFailed(?string $errorMessage = null, ?array $responseData = null): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
            'response_data' => $responseData,
        ]);
    }
}
