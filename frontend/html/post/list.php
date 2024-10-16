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
	<div class="col-xs-12">
	<?php if(!empty($this->getNews())){ ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-envelope"></i> <?php echo translator::trans('news'); ?>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link tooltips" title="<?php echo translator::trans('search'); ?>" href="#search" data-toggle="modal" data-original-title=""><i class="fa fa-search"></i></a>
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
								$statusClass = utility::switchcase($row->status, [
									'label label-success' => newpost::published,
									'label label-warning' => newpost::unpublished
								]);
								$statusTxt = utility::switchcase($row->status, [
									'new.published' => newpost::published,
									'new.unpublished' => newpost::unpublished
								]);
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
		<div class="modal fade" id="search" tabindex="-1" data-show="true" role="dialog">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo translator::trans('search'); ?></h4>
			</div>
			<div class="modal-body">
				<form id="news-post-search" class="form-horizontal" action="<?php echo userpanel\url("news"); ?>" method="GET">
					<?php
					$this->setHorizontalForm('sm-3','sm-9');
					$feilds = [
						[
							'name' => 'id',
							'type' => 'number',
							'ltr' => true,
							'label' => translator::trans("news.post.id")
						],
						[
							'name' => 'title',
							'label' => translator::trans("news.title")
						],
						[
							'name' => 'author',
							'type' => 'hidden'
						],
						[
							'name' => 'author_name',
							'label' => translator::trans("news.author")
						],
						[
							'name' => 'word',
							'label' => translator::trans("news.post.wordKey")
						],
						[
							'type' => 'select',
							'label' => translator::trans('search.comparison'),
							'name' => 'comparison',
							'options' => $this->getComparisonsForSelect()
						]
					];
					foreach($feilds as $input){
						$this->createField($input);
					}
					?>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" form="news-post-search" class="btn btn-success"><?php echo translator::trans("search"); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo translator::trans('cancel'); ?></button>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
<?php
$this->the_footer();
