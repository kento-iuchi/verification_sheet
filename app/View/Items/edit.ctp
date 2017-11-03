<h2>項目を編集</h2>

<table>
    <tr>
        <?php echo $this->Form->create('Item', array('url' => 'edit'));?>
        <td></td>
        <td><?php echo $this->Form->input('id', array('type' => 'hidden'));?></td>
        <td><?php echo $this->Form->input('category', array('label' => false));?></td>
        <td><?php echo $this->Form->input('division', array('label' => false));?></td>
        <td><?php echo $this->Form->input('content', array('label' => false));?></td>
        <td><?php echo $this->Form->input('chatwork_url', array('label' => false));?></td>
        <td><?php echo $this->Form->input('github_url', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_priority', array('label' => false));?></td>
        <td><?php echo $this->Form->input('pullrequest', array('label' => false));?></td>
        <td><?php echo $this->Form->input('pullrequest_update', array('label' => false));?></td>
        <td><?php echo $this->Form->input('status', array('label' => false));?></td>
        <td><?php echo $this->Form->input('tech_release_judgement', array('label' => false));?></td>
        <td><?php echo $this->Form->input('supp_release_judgement', array('label' => false));?></td>
        <td><?php echo $this->Form->input('sale_release_judgement', array('label' => false));?></td>
        <td><?php echo $this->Form->input('scheduled_release_date', array('label' => false));?></td>
        <td><?php echo $this->Form->input('grace_days_of_verification_complete', array('label' => false));?></td>
        <td><?php echo $this->Form->input('merge_finish_date_to_master', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_priority', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_comment', array('label' => false));?></td>
        <td><?php echo $this->Form->input('response_to_confirm_comment', array('label' => false));?></td>
        <td><?php echo $this->Form->end('編集完了');?></td>
    </tr>
</table>
