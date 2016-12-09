<?php
use \packages\base;
use \packages\base\translator;
use \packages\userpanel;
use \packages\userpanel\date;
use \themes\clipone\utility;
use \packages\news\newpost;
$this->the_header();
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-envelope"></i> <?php echo translator::trans('news'); ?>
				<div class="panel-tools">
					<?php if($this->canAdd){ ?>
					<a class="btn btn-xs btn-link tooltips" title="<?php echo translator::trans('news.add'); ?>" href="<?php echo userpanel\url('news/add'); ?>"><i class="fa fa-plus"></i></a>
					<?php } ?>
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<?php
						$hasButtons = $this->hasButtons();
						?>
						<thead>
							<tr>
								<th class="center">#</th>
								<th><?php echo translator::trans('news.title'); ?></th>
								<th><?php echo translator::trans('news.date'); ?></th>
								<th><?php echo translator::trans('news.author'); ?></th>
								<th><?php echo translator::trans('news.view'); ?></th>
								<th><?php echo translator::trans('news.comments'); ?></th>
								<th><?php echo translator::trans('news.status'); ?></th>
								<?php if($hasButtons){ ?><th></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($this->getNews() as $row){
								$this->setButtonParam('news_edit', 'link', userpanel\url("news/edit/".$row->id));
								$this->setButtonParam('news_delete', 'link', userpanel\url("news/delete/".$row->id));
								$statusClass = utility::switchcase($row->status, array(
									'label label-success' => newpost::published,
									'label label-warning' => newpost::unpublished
								));
								$statusTxt = utility::switchcase($row->status, array(
									'new.published' => newpost::published,
									'new.unpublished' => newpost::unpublished
								));
								$comment = '#';
								if(count($row->comments) > 0){
									$comment = userpanel\url("news/comments/".$row->id);
								}
							?>
							<tr>
								<td class="center"><?php echo $row->id; ?></td>
								<td><a href="<?php echo base\url("news/view/".$row->id); ?>"><?php echo $row->title; ?></a></td>
								<td class="ltr"><?php echo date::format('Y/m/d H:i', $row->date); ?></td>
								<td><a href="<?php echo userpanel\url('users/view/'.$row->author->id); ?>"><?php echo $row->author->name." ".$row->author->lastname; ?></a></td>
								<td><span class="badge"><?php echo $row->view; ?></span></td>
								<td><a class="badge" href="<?php echo $comment  ?>"><?php echo count($row->comments); ?></a></td>
								<td class="hidden-xs"><span class="<?php echo $statusClass; ?>"><?php echo translator::trans($statusTxt); ?></span></td>
								<?php
								if($hasButtons){
									echo("<td class=\"center\">".$this->genButtons()."</td>");
								}
								?>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
				<?php $this->paginator(); ?>
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
	</div>
</div>
<?php
$this->the_footer();
