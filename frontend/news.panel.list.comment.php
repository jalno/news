<?php
use \packages\base;
use \packages\base\translator;

use \packages\userpanel;
use \packages\userpanel\user;
use \packages\userpanel\date;

use \themes\clipone\utility;

use \packages\news\newpost;
use \packages\news\comment;


$this->the_header();
?>
<div class="row">
	<div class="col-md-12">
		<!-- start: BASIC TABLE PANEL -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-envelope"></i> <?php echo translator::trans('comments.unpublished'); ?>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
			</div>
			<div class="panel-body">
				<?php if(!empty($this->getComments())){ ?>
				<div class="table-responsive">
					<table class="table table-hover">
						<?php
						$hasButtons = $this->hasButtons();
						?>
						<thead>
							<tr>
								<th class="center">#</th>
								<th><?php echo translator::trans('comments.text'); ?></th>
								<th><?php echo translator::trans('comments.post'); ?></th>
								<th><?php echo translator::trans('comments.date'); ?></th>
								<th><?php echo translator::trans('comments.name'); ?></th>
								<th><?php echo translator::trans('comments.email'); ?></th>
								<th><?php echo translator::trans('comments.status'); ?></th>
								<?php if($hasButtons){ ?><th></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($this->getComments() as $row){
								$this->setButtonParam('comment_edit', 'link', userpanel\url("news/comment/edit/".$row->id));
								$this->setButtonParam('comment_delete', 'link', userpanel\url("news/comment/delete/".$row->id));
								$statusClass = utility::switchcase($row->status, array(
									'label label-warning' => comment::pending,
									'label label-success' => comment::accepted,
									'label label-danger' => comment::unverified
								));
								$statusTxt = utility::switchcase($row->status, array(
									'comment.pending' => comment::pending,
									'comment.accepted' => comment::accepted,
									'comment.unverified' => comment::unverified
								));
							?>
							<tr>
								<td class="center"><?php echo $row->id; ?></td>
								<td><?php echo $row->text; ?></td>
								<td><a href="<?php echo base\url("news/view/".$row->new->id); ?>"><?php echo $row->new->title; ?></a></td>
								<td><?php echo date::format('Y/m/d H:i', $row->date); ?></td>
								<td><?php echo $row->name; ?></td>
								<td><?php echo $row->email; ?></td>
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
			<?php }else{ ?>
					<p><?php echo translator::trans("No.comments.unpublished"); ?></p>
			<?php } ?>
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
	</div>
</div>
<?php
$this->the_footer();
