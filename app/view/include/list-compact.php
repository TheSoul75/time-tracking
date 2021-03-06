<?php
if ($this->data->count()) {
	echo HtmlFormHelper::iForm('tasks');
?>
<table>
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th<?php
				if ($this->order == 'deadline') echo ' class="active"';
			?>><a href="<?php echo $this->fc->getUrl('task','main',array('order'=>'deadline')); ?>"><?php TR::phtml('form','deadline'); ?></a></th>
			<th<?php
				if ($this->order == 'priority') echo ' class="active"';
			?>>
				<small><?php 
					$ct =  $this->data->total();
					echo $ct.' '.TR::html('data',($ct>1)?'items_found':'item_found');
				?></small>
				<a href="<?php echo $this->fc->getUrl('task','main',array('order'=>'priority')); ?>"><?php TR::phtml('ui','task'); ?></a>
			</th>
			<th<?php
				if ($this->order == 'start') echo ' class="active"';
			?>><a href="<?php echo $this->fc->getUrl('task','main',array('order'=>'start')); ?>"><?php TR::phtml('form','start'); ?></a></th>
			<th<?php
				if ($this->order == 'stop') echo ' class="active"';
			?>><a href="<?php echo $this->fc->getUrl('task','main',array('order'=>'stop')); ?>"><?php TR::phtml('form','stop'); ?></a></th>
			<th<?php
				if ($this->order == 'spent') echo ' class="active"';
			?>><a href="<?php echo $this->fc->getUrl('task','main',array('order'=>'spent')); ?>"><?php TR::phtml('task','spent'); ?></a></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$arr = array();
	$i = $total = 0;
	$cid = ($this->current)?$this->current->getUid():0;
	while ($this->data->next()) {
		$this->data->chkDeadline();
		$id = $this->data->getUid();
		echo '<tr id="tr_'.$id.'"'
			.((!$this->filter)?$this->data->curCss(($cid == $id)?'current':''):'')
			.'>';
		echo '<td>';
			if (!in_array($id, $arr)) {
				echo '<input type="checkbox" id="chk_'.$i.'" name="chk[]" '
					.'value="'.$id.'" />';
				$i++;
				$arr[] = $id;
			} else {
				echo '<input type="checkbox" disabled="disabled" />';
			}
			$total += $this->data->get('spent');
		?></td>
			<td><?php echo $this->data->htmlDeadline(); ?></td>
			<td>
				<?php
				// edit link
				echo '<a href="'.$this->fc->getUrl('task','edit',array('id'=>$id)).'" class="onhold ajax box" title="'
					.TR::html('ui','edit_task').'">'.TR::html('button','edit').'</a>';
				// priority
				echo '<span class="prio pr'.$this->data->get('priority');
				echo '" title="'.$this->data->htmlPriority().'">'.$this->data->get('priority').'</span> ';
				// note
				echo '<a href="'.$this->fc->getUrl('task','view',array('id'=>$id)).'" ';
					if ($this->data->isEmpty('note')) {
						echo 'class="ajax box clickme"';
					} else {
						echo 'class="note ajax box clickme" ';
						echo 'title="'.$this->data->html('note',200).'"';
					}
				echo '>';
				// title
				echo $this->data->html('title');
				echo '</a>';
				?>
			</td>
			<td><?php echo $this->data->htmlBegin(); ?></td>
			<td><?php echo $this->data->htmlEnd(); ?></td>
			<td id="sts_<?php echo $id; ?>">
				<?php
				if ($this->data->isOpened($this->user_id)) {
					echo '<a href="'.$this->fc->getUrl('task','timer',array('id'=>$id)).'" '
						.'class="onhold clock ajax" title="'.TR::html('ui','start_task').'" rel="drun">'.TR::html('button','start').'</a>';
				}
				
				echo '<span>';
				if (!$this->expand && $cid == $id) {
					echo TR::html('task','running');
				} else {
					echo $this->data->getTimeSpent();
				}
				echo '</span>';
				?>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3">
				<a href="javascript:checkAll('f_tasks')"><?php TR::phtml('ui','select_all'); ?></a> |
				<?php
				foreach ($this->actions as $key => $label) {
					echo ' <button type="submit" name="'.$key.'" '
						.'value="1">'.TR::html('button',$label).'</button>';
				}
				?>
			</td>
			<td colspan="2"><?php TR::phtml('ui','total'); ?></td>
			<td><?php echo TaskSummary::htmlTime($total); ?></td>
		</tr>
	</tfoot>
</table>
</form>
<?php
} else {
	echo '<p class="empty">'.TR::html('error','search_empty').'</p>';
}
