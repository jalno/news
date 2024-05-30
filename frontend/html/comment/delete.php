<?php
use packages\base\Translator;
use packages\userpanel;

$this->the_header();
?>
<div class="row">
	<div class="col-md-12">
		<!-- start: BASIC DELETE NEW -->
		<form action="<?php echo userpanel\url('news/comment/delete/'.$this->getComment()->id); ?>" method="POST">
			<div class="alert alert-block alert-warning fade in">
				<h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> <?php echo Translator::trans('attention'); ?>!</h4>
				<p>
					<?php echo Translator::trans('comment.delete.warning', ['comment.id' => $this->getComment()->id]); ?>
				</p>
				<p>
					<a href="<?php echo userpanel\url('news/comments'); ?>" class="btn btn-light-grey"><i class="fa fa-chevron-circle-right"></i> <?php echo Translator::trans('return'); ?></a>
					<button type="submit" class="btn btn-yellow"><i class="fa fa-trash-o tip"></i> <?php echo Translator::trans('delete'); ?></button>
				</p>
			</div>
		</form>
		<!-- end: BASIC DELETE NEW  -->
	</div>
</div>
<?php
$this->the_footer();
