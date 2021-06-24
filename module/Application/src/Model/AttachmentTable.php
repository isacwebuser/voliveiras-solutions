<?php


namespace Application\Model;

use Core\Model\AbstractCoreModelTable;

class AttachmentTable extends AbstractCoreModelTable
{
    public function findAll(array $params)
    {
        return $this->tableGatway->select($params);
    }
    public function saveAttachment (array $data, Ticket $ticket)
    {
        $attachments = $data['attachment'];
        unset($data['attachment']);

        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $tmp_name = explode('/',$attachment['tmp_name']);
                $this->save([
                    'name' => $attachment['name'],
                    'file' => end($tmp_name),
                    'ticket' => $ticket->id
                ]);
            }
        }
    }

    public function deleteAttachment ($ticketId)
    {
        $attachments = $this->findAll([
            'ticket' => $ticketId
        ]);

        $dirUploaded = __DIR__ . '/../../../../public/upload/';

        foreach ($attachments as $attachment) {
            $this->delete($attachment->id);
            unlink($dirUploaded . $attachment->file);
        }
    }
}