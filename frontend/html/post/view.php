<?php
use \packages\base\translator;
use \packages\userpanel\date;
$this->the_header();
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-newspaper-o"></i>
                <?php echo $this->new->title; ?>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
            </div>
            <div class="panel-body">
				<?php echo $this->new->content ?>
			</div>
			<div class="panel-footer"><?php echo translator::trans('news.date').':'.date::format("l j F Y", $this->new->date); ?></div>
        </div>
    </div>
</div>
<?php $this->the_footer();
