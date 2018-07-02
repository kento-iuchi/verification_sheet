<?php
class EditingItemsController extends AppController
{
    // define('UNREGISTER_INTERVAL', )
    // modifiedから一定時間経過しているレコードを削除
    public function unregister_left_editing_item()
    {
        $current_datetime = new Datetime(date("y-m-d H:i:s"));
        $current_datetime = $current_datetime->getTimestamp();
        $ids_and_their_modified = Hash::combine($this->find('all'), '{n}.EditingItem.id', '{n}.EditingItem.modified');
        if (empty($ids_and_their_modified)) {
            return false;
        }

        foreach ($ids_and_their_modified as $id => $modified) {
            $modified = new Datetime($modified);
            $modified = $modified->getTimestamp();

            if (($current_datetime - $modified) > 10800000) {
                if ($this->EditingItem->delete($id) {
                    $this->log("delete editing item record[{$id}]");
                } else {
                    $this->log("failed to delete editing item record[{$id}]");
                }
            }
        }
    }
}
