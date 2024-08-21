var app = angular.module('plunker', ['ui.sortable', 'angularFileUpload', 'textAngular', 'videosharing-embed']);


app.controller('MyCtrl', ['$scope', '$upload', function ($scope, $upload) {
    $scope.$watch('files', function () {
        $scope.upload($scope.files);
    });

$scope.upload = function(files) {
  if (files && files.length) {
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      files.upload = $upload.upload({
          url: './king-include/king-upload.php',
          headers: {
            'Content-Type': file.type
          },
          method: 'POST',
          data: file,
          file: file,
        })
        .progress(function(evt) {
          $scope.fileUploadingPercent = parseInt(100.0 * evt.loaded / evt.total);
        })
        .success(function(data, status, headers, config) {
          console.log('file ' + config.file.name + 'uploaded. Response: ' + data);
          $scope.picture = data;
		  files.result = data;
        });

    }
  }
};	

	
$scope.inputs = [  {    
  vtitle:'',
  vimage:'',
  vcontent:'',
  
}];	

$scope.set = function() {
    this.item.vimage = $scope.picture ;
	this.imgpre = $scope.picture;
	this.hclass = 'adimage';
}
$scope.set2 = function() {
    this.item.vimage = this.item.video;
	this.videopre = this.item.video;
	this.hclass = 'advideo';
}

$scope.addInput = function(){
    $scope.inputs.push({
      vtitle:'',
      vimage:'',
      vcontent:'',
    });
}


$scope.removeInput = function(index){
    $scope.inputs.splice(index,1);
}

$scope.sortableOptions = {
    handle: 'div .myHandle',
    stop: function(e, ui) {$scope.inputChanged()},
    axis: 'y'
};
        
$scope.inputChanged = function() {
  $scope.listString = $scope.content+" ";
  $scope.extratext = $scope.extradiv+" ";
  for(var i = 0; i < $scope.inputs.length; i++) {
    if($scope.checked){
    var addnumbers = i+1+".";
    } else {
    addnumbers = "";  
    }
    var vtitle = $scope.inputs[i].vtitle;
    var vimage = $scope.inputs[i].vimage;
    var vcontent = $scope.inputs[i].vcontent;
    $scope.listString += "\n" + "<title>" + addnumbers + vtitle + "</title>" + "<pimage>" + vimage + "</pimage>" + "<content>" + vcontent + "</content> ";
  }
}
}]);