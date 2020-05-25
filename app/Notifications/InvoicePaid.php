<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Restaurante;
use App\Pedido;


class InvoicePaid extends Notification
{
    use Queueable;

    public $id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($idPedido)
    {
        $this->id = $idPedido;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    

    public function toMail($notifiable)
    {
        $restaurante = Restaurante::first();
        $pedido = Pedido::find($this->id);
        //$detalles = DB::table('detalle_pedido')->where('idPedido','=',$this->id)->get();

        return (new MailMessage)
                            ->subject('Pedido Listo')
                            ->greeting($restaurante->nombre)
                            ->line('Tu pedido esta listo para ser entregado!')
                            ->line('N° pedido: '.$pedido->numeroPedido.'  '.'Fecha: '.$pedido->fechahoraPedido)
                            ->line('Cliente: '.$pedido->nombreCliente.'  '.'CI/RUC: '.$pedido->numeroDocumento)
                            ->line('Total: '.$pedido->totalPedido.' $')
                            ->line('**********************************************')
                            ->action('Revisar','#')
                            ->line('Gracias por preferirnos!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
