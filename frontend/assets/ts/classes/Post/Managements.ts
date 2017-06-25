import * as $ from "jquery";
import "../jquery.userAutoComplete";
import "dropzone";
import "jquery-inputlimiter";
import { AjaxRequest , webuilder } from "webuilder";
import "jquery.growl";
import "bootstrap-inputmsg";
import "bootstrap-tagsinput";
import "bootstrap-avatar-preview";
import "jquery.fancytree/dist/jquery.fancytree-all-deps.min.js";
import { AvatarPreview } from 'bootstrap-avatar-preview/AvatarPreview';
declare const CKEDITOR:any;
export default class Managements{
	private static $form:JQuery = $('#news-post-form');
	private static $uploadZone:JQuery = $('.upload-zone', Managements.$form);
	private static runUserSearch(){
		$('input[name=author_name]', Managements.$form).userAutoComplete();
	}
	private static runInputLimiter(){
		$('input[name=title]').inputlimiter({
            remText: '%n کارکتر باقی مانده است.',
            remFullText: 'بهتر است دست از نوشتن بردارید، شما از محدود پیشنهاد شده عبور کردید.',
            limitText: 'پیشنهاد میشود در این قسمت بیش از %n کارکتر وارد نکنید.',
			allowExceed: true,
			limit:60
        });
		$('textarea[name=description]').inputlimiter({
            remText: '%n کارکتر باقی مانده است.',
            remFullText: 'بهتر است دست از نوشتن بردارید، شما از محدود پیشنهاد شده عبور کردید.',
            limitText: 'پیشنهاد میشود در این قسمت بیش از %n کارکتر وارد نکنید.',
			allowExceed: true,
			limit:160
        });
	}
	private static humanFriendlySize(size:number){
		if(size < 1024){
			return size + ' بایت';
		}else if(size < 1024*1024){
			return Math.round(size / 1024) + ' کیلوبایت';
		}else if(size < 1024*1024*1024){
			return Math.round(size / 1024 / 1024) + ' مگابایت';
		}else if(size < 1024*1024*1024*1024){
			return Math.round(size / 1024 / 1024 / 1024) + ' گیگابایت';
		}else{
			return Math.round(size / 1024 / 1024 / 1024 / 1024) + ' ترابایت';
		}
	}
	private static getIcon(file){
		switch(file.type){
			case('text/html'):
			case('text/css'):
			case('text/xml'):
			case('application/javascript'):
			case('application/atom+xml'):
			case('application/rss+xml'):
			case('text/mathml'):
			case('application/json'):
			case('application/x-perl'):
			case('application/xhtml+xml'):
				return 'fa fa-file-code-o';
			case('image/gif'):
			case('image/png'):
			case('image/jpeg'):
			case('image/tiff'):
			case('image/x-icon'):
			case('image/x-jng'):
			case('image/x-ms-bmp'):
			case('image/svg+xml'):
			case('image/webp'):
				return 'fa fa-file-image-o';

			case('text/plain'):
			case('text/rtf'):
				return 'fa fa-file-text-o';
			case('application/msword'):
			case('application/vnd.openxmlformats-officedocument.wordprocessingml.document'):
				return 'fa fa-file-word-o';
			case('application/vnd.ms-excel'):
			case('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'):
				return 'fa fa-file-excel-o';
			case('application/vnd.ms-powerpoint'):
			case('application/vnd.openxmlformats-officedocument.spreadsheetml.presentation'):
				return 'fa fa-file-powerpoint-o';
			case('application/pdf'):
				return 'fa fa-file-pdf-o';
			case('application/zip'):
			case('application/x-rar-compressed'):
			case('application/x-7z-compressed'):
				return 'fa fa-file-archive-o';
			case('video/3gpp'):
			case('video/mp4'):
			case('video/mpeg'):
			case('video/quicktime'):
			case('video/webm'):
			case('video/x-flv'):
			case('video/x-m4v'):
			case('video/x-mng'):
			case('video/x-ms-wmv'):
			case('video/x-msvideo'):
				return 'fa fa-file-video-o';
			case('audio/midi'):
			case('audio/mpeg'):
			case('audio/ogg'):
			case('audio/x-m4a'):
			case('audio/x-realaudio'):
				return 'fa fa-file-audio-o';
			default:
				return 'fa fa-file-o';
		}
	}
	private static removeFileFromUploadZone(e){
		e.preventDefault();
		const $tr:JQuery = $(this).parents('tr');
		if($tr.data('id')){
			AjaxRequest({
				url: 'userpanel/news/files/delete/' + $tr.data('id') + '?ajax=1',
				type: 'post',
				success: (data: webuilder.AjaxResponse) => {
					$('input[name="attachment[]"]', Managements.$form).each(function(){
						if($(this).val() == $tr.data('id')){
							$(this).remove();
							return false;
						}
					})
					$tr.remove();
					if($('tr', Managements.$uploadZone).length == 0){
						Managements.$uploadZone.addClass('no-file');
					}
				},
				error: function(error:webuilder.AjaxError){
					$.growl.error({
						title:"خطا",
						message:'متاسفانه خطایی بوجود آمده'
					});
				}
			});
		}else{
			$tr.remove();
			if($('tr', Managements.$uploadZone).length == 0){
				Managements.$uploadZone.addClass('no-file');
			}
		}
	}
	private static uploadFileFromUploadZone(e){
		e.preventDefault();
		let $btn:JQuery = $(this);
		if($btn.data('disabled')){
			return false;
		}
		$btn.data('disabled', true);
		const $tr:JQuery = $(this).parents('tr');
		let file = $tr.data('file');
		let data:FormData = new FormData();
		data.append("file", file);
		const $bar:JQuery = $('.progress-bar', $tr);
		function uploadProgress(loaded:number, max:number){
			let percentComplete:number = (loaded / max) * 100;
			$bar.width(percentComplete + '%');
			$bar.html(Math.round(percentComplete) + '%');
			$bar.data('valuenow', percentComplete);
			if(percentComplete == 100){
				$bar.addClass('progress-bar-success');
			}
		}
		AjaxRequest({
			url: 'userpanel/news/files/upload?ajax=1',
			type: 'post',
			data: data,
			processData: false,
			contentType: false,
			xhr: function() {
			   let xhr = $.ajaxSetup({}).xhr(); 
			   if(xhr.upload) {
				   xhr.upload.addEventListener('progress', function(e) {
					   if (e.lengthComputable) {
						  uploadProgress(e.loaded, e.total);
					   }
				   } , false);
			   }
			   return xhr;
		   },
			beforeSend: function() {
				uploadProgress(0, 100);
			},
			success: (data: webuilder.AjaxResponse) => {
				$('input[name="attachment[]"]', Managements.$form).each(function(){
					if($(this).val() == $tr.data('id')){
						$(this).remove();
						return false;
					}
				})
				let fileObject;
				for(let i = 0; i != data.files.length && fileObject == undefined; i++){
					if(data.files[i].name == file.name){
						fileObject = data.files[i];
					}
				}
				if(fileObject){
					uploadProgress(100, 100);
					$tr.data('id', fileObject.id);
					$('.btn-upload', $tr).hide();
					$('.btn-filelink', $tr).show().attr('href', fileObject.url);
					$('.progress', $tr).parent().html('<input type="url" class="ltr" value="'+fileObject.url+'">');
					Managements.$form.prepend('<input type="hidden" name="attachment[]" value="'+$tr.data('id')+'">');
				}
			},
			error: function(error:webuilder.AjaxError){
				$.growl.error({
					title:"خطا",
					message:'متاسفانه خطایی بوجود آمده'
				});
			}
		});
	}
	private static addFileToUploadZone(input:JQuery, file){
		if($('table.files tr', Managements.$uploadZone)){
			Managements.$uploadZone.removeClass('no-file');
		}
		let $panel:JQuery = $('.panel', Managements.$uploadZone);
		let $html:string  = '<tr>';
		 	$html += '<td class="center"><i class="file-icon '+Managements.getIcon(file)+'"></i></td>';
		 	$html += '<td class="col-xs-4 ltr filename">'+file.name+'</td>';
		 	$html += '<td class="col-xs-2">'+Managements.humanFriendlySize(file.size)+'</td>';
		 	$html += '<td class="col-xs-3">';
			 	$html += '<div class="progress">';
			 		$html += '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>';
			 	$html += '</div>';
		 	$html += '</td>';
		 	$html += '<td class="center">';
			 	$html += '<div class="visible-md visible-lg hidden-sm hidden-xs">';
				 	$html += '<a href="#" class="btn btn-xs btn-upload btn-success tooltips" title="آپلود"><i class="fa fa-upload"></i></a> ';
				 	$html += '<a href="#" target="_blank" class="btn btn-xs btn-filelink btn-info tooltips" title="لینک مستقیم"><i class="fa fa-link"></i></a> ';
					if(Managements.$uploadZone.data('can-delete'))$html += '<a href="#" class="btn btn-xs btn-delete btn-bricky tooltips" title="حذف"><i class="fa fa-trash-o"></i></a>';
			 	$html += '</div>';
				$html += '<div class="visible-xs visible-sm hidden-md hidden-lg">';
					$html += '<div class="btn-group">';
						$html += '<a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <span class="caret"></span></a>';
					$html += '</div>';
					$html += '<ul role="menu" class="dropdown-menu pull-right">';
						$html += '<li><a tabindex="-1" href="#" class="btn-upload"><i class="fa fa-upload"></i> آپلود</a></li>';
						$html += '<li><a tabindex="-1" href="#" target="_blank" class="btn-filelink"><i class="fa fa-link"></i> لینک مستقیم</a></li>';
						if(Managements.$uploadZone.data('can-delete'))$html += '<li><a tabindex="-1" href="#" class="btn-delete"><i class="fa fa-trash-o"></i> حذف</a></li>';
					$html += '</ul>';
				$html += '</div>';
			$html += '</td>';
			$html += '</tr>';
		let $tr:JQuery = $($html).appendTo($('tbody', Managements.$uploadZone));
		$tr.data('file', file);
		$('.tooltips', $tr).tooltip();
		$('.btn-delete', $tr).on('click', Managements.removeFileFromUploadZone);
		$('.btn-upload', $tr).on('click', Managements.uploadFileFromUploadZone);
		$('.btn-filelink', $tr).hide();
	}
	private static handleNewFiles
	private static runUploadZone(){
		let $input:JQuery = $('input[name=file]', Managements.$form);
		$input.on('change', function(){
			const files = (<HTMLInputElement>this).files;
			if(files.length){
				const $input = $(this);
				for(let i = 0;i != files.length;i++){
					Managements.addFileToUploadZone($input, files[i]);
				}
				$input.val("");
			}
		});
		$('.btn-add', Managements.$uploadZone).on('click', function(e){
			e.preventDefault();
			$('input[name=file]', Managements.$form).click();
		});
		$('.btn-delete', Managements.$uploadZone).on('click', Managements.removeFileFromUploadZone);
	}
	private static runAvatarPreview(){
		new AvatarPreview($('.post-thumbnail-image', Managements.$form));
	}
	private static runSubmitFormListener = function(){
		Managements.$form.on('submit', function(e){
			e.preventDefault();
			let data:FormData = new FormData(this as HTMLFormElement);
			data.set('content', CKEDITOR.instances['content'].getData());
			$(this).formAjax({
				data: data,
				contentType: false,
				processData: false,
				success: (data: webuilder.AjaxResponse) => {
					$.growl.notice({
						title:"موفق",
						message:"انجام شد ."
					});
					if(data.redirect){
						window.location.href = data.redirect;
					}
				},
				error: function(error:webuilder.AjaxError){
					if(error.error == 'data_duplicate' || error.error == 'data_validation'){
						let $input = $('[name='+error.input+']');
						let $params = {
							title: 'خطا',
							message:''
						};
						if(error.error == 'data_validation'){
							$params.message = 'داده وارد شده معتبر نیست';
						}
						if($input.length){
							$input.inputMsg($params);
						}else{
							$.growl.error($params);
						}
					}else{
						$.growl.error({
							title:"خطا",
							message:'درخواست شما توسط سرور قبول نشد'
						});
					}
				}
			});
		});
	}
	public static init(){
		Managements.runInputLimiter();
		Managements.runUploadZone();
		Managements.runAvatarPreview();
		Managements.runSubmitFormListener();
	}
	public static initIfNeeded(){
		if(Managements.$form.length){
			Managements.init();
		}
	}
}