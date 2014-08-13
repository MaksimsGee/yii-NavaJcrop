This extension is a wrapper of [JavaScript-Load-Image](http://blueimp.github.io/JavaScript-Load-Image/ "JavaScript-Load-Image")

It's very easy to use, was made because I tried jcrop, jii-crop but it is not what I need.

##Requirements

Yii 1.1.x or above

##Usage

extract all file to **application/extensions**

**View** file:
~~~
$this->widget('ext.NavaJcrop.ImageJcrop', array(
	'config' => array(
		'title'=>$model->name,
		'image'=>$model->image_url,//required, all field below are not required.
		/*'id'=>'nava-jcrop',
		'unique'=>true,
		'buttons'=>array(
			'cancel'=>array(
				'name'=>'Cancel',
				'class'=>'button-crop',
				'style'=>'margin-left: 5px;',
			),
			'edit'=>array(
				'name'=>'Edit',
				'class'=>'button-crop',
				'style'=>'margin-left: 5px;',
			),
			'crop'=>array(
				'name'=>'Crop',
				'class'=>'button-crop',
				'style'=>'margin-left: 5px;',
			)
		),
		'options'=>array(
			'imageWidth'=>150,
			'imageHeight'=>175,
			'resultStyle'=>'position: fixed;top: 50px;max-width:350px;max-height:350px;z-index: 9999;',
			'resultMaxWidth'=>350,
			'resultMinWidth'=>350,
		),
		'callBack'=> array(
			'success'=>"function(obj,res){console.log(obj,res);}",
			'error'=>"function(){alert('error');}",
		)
		*/
	)
));
~~~

You can change the callBack:

~~~
'success'=>"function(obj,res){doSomething(obj,res);}",
~~~

and the javascript for it:

~~~
<script>
function doSomething(obj,res){ //the 'obj' is IMG tag, 'res' is base64image
	$.ajax({
		cache: false,
		type: 'post',
		url: <?php echo Yii::app()->createUrl('site/upload');?>,
		data: 'image='+res,
		success: function(){
			obj.attr('src',res);
		}
	});
}
</script>
~~~

Controller code to upload this image:

~~~
public function actionUpload(){
	$model = Model::model()->findByPk(1);
	if(isset($_POST)){
		$img = $_POST['image'];
		if($img != '/img/noimage.png' && $img != $model->image_url){
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$data = base64_decode($img);
			$file = '/uploads/images/'.$model->id.'.jpg';
			$model->image_url = Yii::app()->request->hostInfo.'/'.Yii::app()->baseUrl.$file;
			file_put_contents(Yii::getPathOfAlias('webroot').$file, $data);
		}
	}
}
~~~
