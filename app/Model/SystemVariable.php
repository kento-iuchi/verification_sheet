<?php
class SystemVariable extends AppModel
{
    public function resetNextReleaseDate()
    {
        $first_date = date('Y-m-14');
        $dow_of_14 = date('w', strtotime($first_date));
        $second_mon = 14 - $dow_of_14;
        $second_mon = sprintf('%02d', $second_mon);

        return date("Y-m-{$second_mon}");
    }

}
