<?php


namespace Core\Mail;

use Laminas\View\View;
use Laminas\Mail\Message;
use Laminas\Mime\Part as MimePart;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mail\Transport\Smtp as SmtpTransport;


Abstract class AbstractCoreMail
{
    protected $transport;
    protected $view;
    protected $body;
    protected $message;
    protected $subject;
    protected $to;
    protected $replyTo;
    protected $data;
    protected $page;
    protected $cc;


    public function __construct(SmtpTransport $transport, View $view, $page)
{
    $this->transport = $transport;
    $this->view = $view;
    $this->page = $page;
}

    public function getTransport()
    {
        return $this->transport;
    }
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function setCc($cc)
    {
        $this->cc = $cc;

        return $this;
    }

    abstract public function renderView($page, array $data);

    public function prepareMail()
    {
        $html = new MimePart($this->renderView($this->page, $this->data));
        $html->type = 'text/html';

        $body = new MimeMessage();
        $body->setParts([$html]);
        $this->body = $body;

        $config = $this->transport->getOptions()->toArray();

        $this->message = new Message();
        $this->message->addFrom($config['connection_config']['from'])
            ->addTo($this->to)
            ->setSubject($this->subject)
            ->setBody($this->body);

        if ($this->cc){
            $this->message->addCc($this->cc);
        }

        if ($this->replyTo){
            $this->message->addCc($this->replyTo);
        }

        return $this;
    }

    public function sendMail()
    {
        $this->transport->send($this->message);
    }

}