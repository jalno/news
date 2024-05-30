<?php
use \packages\base;
use \packages\base\Translator;

use \packages\userpanel;
use \packages\userpanel\User;
use \packages\userpanel\Date;

use \themes\clipone\Utility;

use \packages\news\NewPost;
use \packages\news\Comment;


$this->the_header();
?>
<div class="row">
	<div class="col-md-12">
		<!-- start: BASIC TABLE PANEL -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-envelope"></i> نظر ها
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
								<th><?php echo Translator::trans('comments.text'); ?></th>
								<th><?php echo Translator::trans('comments.post'); ?></th>
								<th><?php echo Translator::trans('comments.date'); ?></th>
								<th><?php echo Translator::trans('comments.name'); ?></th>
								<th><?php echo Translator::trans('comments.email'); ?></th>
								<th><?php echo Translator::trans('comments.status'); ?></th>
								<?php if($hasButtons){ ?><th></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($this->getComments() as $row){
								$this->setButtonParam('comment_edit', 'link', userpanel\url("news/comment/edit/".$row->id));
								$this->setButtonParam('comment_delete', 'link', userpanel\url("news/comment/delete/".$row->id));
								$statusClass = Utility::switchcase($row->status, array(
									'label label-warning' => Comment::pending,
									'label label-success' => Comment::accepted,
									'label label-danger' => Comment::unverified
								));
								$statusTxt = Utility::switchcase($row->status, array(
									'comment.pending' => Comment::pending,
									'comment.accepted' => Comment::accepted,
									'comment.unverified' => Comment::unverified
								));
							?>
							<tr>
								<td class="center"><?php echo $row->id; ?></td>
								<td><?php echo $row->text; ?></td>
								<td><a href="<?php echo base\url("news/view/".$row->new->id); ?>"><?php echo $row->new->title; ?></a></td>
								<td><?php echo Date::format('Y/m/d H:i', $row->date); ?></td>
								<td><?php echo $row->name; ?></td>
								<td><?php echo $row->email; ?></td>
								<td class="hidden-xs"><span class="<?php echo $statusClass; ?>"><?php echo Translator::trans($statusTxt); ?></span></td>
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
					<p><?php echo Translator::trans("No.comments.unpublished"); ?></p>
			<?php } ?>
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
	</div>
</div>
<?php
$this->the_footer();
