<?php
use \packages\base;
use \packages\base\json;
use \packages\base\translator;
use \packages\userpanel;
use \packages\userpanel\date;
use \packages\news\authorization;
use \packages\news\newpost as post;
$this->the_header();
?>
<form id="news-post-form" action="<?php echo userpanel\url('news/add'); ?>" method="post" enctype="multipart/form-data">
	<div class="row">
	    <div class="col-sm-6">
			<?php
			$this->createField(array(
				'name' => 'title',
				'label' => translator::trans("news.title")
			));
			?>
		</div>
	    <div class="col-sm-6">
			<div class="panel panel-white panel-description">
				<div class="panel-heading">
					<i class="fa fa-file-text-o"></i>
					<span><?php echo translator::trans("news.description"); ?></span>
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
					</div>
				</div>
				<div class="panel-body">
					<?php
					$this->createField(array(
						'type' => 'textarea',
						'name' => 'description',
						'rows' => 4
					));
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
	    <div class="col-md-9">
			<div class="row">
				<div class="col-xs-12">
					<div class="panel panel-white panel-text">
						<div class="panel-heading">
							<i class="fa fa-edit"></i>
							<span><?php echo translator::trans("news.text"); ?></span>
							<div class="panel-tools">
								<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
							</div>
						</div>
						<div class="panel-body">
							<?php
							$this->createField(array(
								'name' => 'content',
								'type' => 'textarea',
								'class' => 'ckeditor'
							));
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="upload-zone no-file" data-can-delete="<?php echo(authorization::is_accessed('files_delete') ? 'true' : 'false'); ?>">
						<input type="file" name="file" multiple="mutliple">
						<div class="panel panel-white">
							<div class="panel-heading">
								<i class="fa fa-paperclip"></i>
								<span><?php echo translator::trans("news.post.files"); ?></span>
								<div class="panel-tools">
									<a class="btn btn-xs btn-link btn-add tooltips" href="#" title="<?php echo translator::trans('news.post.files.add'); ?>"><i class="fa fa-plus"></i></a>
									<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
								</div>
							</div>
							<div class="panel-body">
								<table class="table files">
									<tbody></tbody>
								</table>
							</div>
						</div>
						<div class="no-file">
							<i class="fa fa-cloud-upload"></i><?php echo translator::trans('news.post.files.drag&drop'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-white panel-details">
				<div class="panel-heading">
					<i class="fa fa-clock-o"></i>
					<span><?php echo translator::trans("news.post.details"); ?></span>
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
					</div>
				</div>
				<div class="panel-body">
					<input type="hidden" name="author" value="<?php echo $this->getDataForm('author'); ?>">
					<input type="hidden" name="category" value="" class="">
					<?php
					$this->createField(array(
						'name' => 'date',
						'label' => translator::trans("news.post.date"),
						'value' => date::format('Y/m/d H:i', date::time()),
						'class' => 'form-control ltr'
					));
					$this->createField(array(
						'type' => 'select',
						'name' => 'status',
						'label' => translator::trans("news.post.status"),
						'options' => array(
							array(
								'title' => translator::trans("new.published"),
								'value' => post::published
							),
							array(
								'title' => translator::trans("new.unpublished"),
								'value' => post::unpublished
							)
						)
					));
					$this->createField(array(
						'name' => 'author_name',
						'label' => translator::trans("news.post.author")
					));
					?>
				</div>
				<div class="panel-footer">
					<button type="submit" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> <?php echo translator::trans("news.post.save"); ?></button>
				</div>
			</div>
			<div class="panel panel-white panel-thumbnail">
				<div class="panel-heading">
					<i class="fa fa-picture-o"></i>
					<span><?php echo translator::trans("news.post.thumbnail"); ?></span>
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
					</div>
				</div>
				<div class="panel-body">
					<div class="post-thumbnail-image avatarPreview">
						<img src="<?php echo $this->getImage(); ?>" class="preview img-responsive">
						<input name="image" type="file">
						<div class="button-group">
							<button type="button" class="btn btn-teal btn-sm btn-upload"><i class="fa fa-pencil"></i></button>
							<button type="button" class="btn btn-bricky btn-sm btn-remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php
	$this->the_footer();
