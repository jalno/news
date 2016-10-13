<?php
use \packages\base;
use \packages\base\translator;

use \packages\userpanel;
use \packages\userpanel\user;
use \packages\userpanel\date;

use \themes\clipone\utility;

use \packages\news\newpost;


$this->the_header();
?>
<div class="row">
	<div class="col-md-12">
		<!-- start: BASIC TABLE PANEL -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-envelope"></i> <?php echo translator::trans('news.published'); ?>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link tooltips" title="<?php echo translator::trans('news.add'); ?>" href="<?php echo userpanel\url('news/new'); ?>"><i class="fa fa-plus"></i></a>
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
								<th><?php echo translator::trans('news.data'); ?></th>
								<th style="width: 37%;"><?php echo translator::trans('news.description'); ?></th>
								<th><?php echo translator::trans('news.author'); ?></th>
								<th><?php echo translator::trans('news.view'); ?></th>
								<th><?php echo translator::trans('news.comments') ?></th>
								<?php if($hasButtons){ ?><th></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($this->getNews() as $row){
								$this->setButtonParam('news_view', 'link', userpanel\url("news/view/".$row->id));
								$this->setButtonParam('news_edit', 'link', userpanel\url("news/edit/".$row->id));
								$this->setButtonParam('news_delete', 'link', userpanel\url("news/delete/".$row->id));
							?>
							<tr>
								<td class="center"><?php echo $row->id; ?></td>
								<td><?php echo $row->title; ?></td>
								<td><?php echo date::format('Y/m/d H:i', $row->date); ?></td>
								<td><?php echo $row->description; ?></td>
								<td><a href="<?php echo userpanel\url('users/view/'.$row->author->id); ?>"><?php echo $row->author->name." ".$row->author->lastname; ?></a></td>
								<td><?php echo $row->view; ?></td>
								<?php if(count($row->comments) > 0){ ?>
								<td><a href="<?php echo userpanel\url("news/comments/".$row->id); ?>"><?php echo count($row->comments); ?></a></td>
								<?php
								}else{
								?>
								<td><?php echo count($row->comments); ?></td>
								<?php
								}
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
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
	</div>
</div>
<?php
$this->the_footer();
