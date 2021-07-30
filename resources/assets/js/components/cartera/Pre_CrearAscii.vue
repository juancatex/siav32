<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
      

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> <div class="breadcrumbs" style="display: inline-block;"></div></div>
                        <div class="card-body">
                            <div class="filemanager">  
                                <ul class="data"></ul>

                                <div class="nothingfound">
                                    <div class="nofiles"></div>
                                    <span>No existen archivos</span>
                                </div>

                            </div>
                        </div>
                </div>
            </div>
			 <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Generar ASCII</div>
                    <div class="card-body">
                        <div class="form-group row" style="display: block;text-align: center;">
							<button class="btn btn-outline-primary active" type="button"
                                @click="generarascii">Generar archivo ASCII</button> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

      
<div id="downloadFile" class="hidden">  
   <a class="downloadFileclass"><i class="fa fa-download"></i><span style=" color: white; padding: 12px; ">Descargar</span></a>
</div>

 <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="generarpdf">
      <div class="modal-dialog modal-primary modal-xl" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Detalle de carga ASCII</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModalpdf.closeModal('generarpdf')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body-plandepagos"> 
            <iframe name="viewerpdf" id="viewerpdf" style="width:100%;height:100%;"></iframe>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModalpdf.closeModal('generarpdf')">cerrar</button>
            
          </div>
        </div>
      </div>
    </div>

    </main>
</template>

<script>
export default {
	data() {
		return {
			response: [],
			breadcrumbsUrls: [],
			pathcontrol: '',
			filemanager: null,
			breadcrumbs: null,
			fileList: null,
			classModalpdf: null
		}
	},
	computed: {

	},
	methods: {
		generarascii() {
			let me=this;
			axios.get('/ascii').then(function (response) {
					var status = response.data;
					if (status.status == 'error') {
						swal(status.error, "", "warning");
					} else {
						swal({
							type: 'success',
							title: 'Se genero el archivo correctamente',
							showConfirmButton: false,
							timer: 1500
						}); 
						me.getfiles();
					}
				})
				.catch(function (error) {
					console.log(error);
				});
		},
		ini() {
			this.filemanager = $('.filemanager');
			this.breadcrumbs = $('.breadcrumbs');
			this.fileList = this.filemanager.find('.data');

			let me = this;
			this.fileList.on('click', 'li.folders', function (e) {
				e.preventDefault();
				var nextDir = $(this).find('a.folders').attr('href');
				me.breadcrumbsUrls.push(nextDir);
				me.pathcontrol = nextDir;
				me.goto(nextDir);
			});


			this.breadcrumbs.on('click', 'a', function (e) {
				e.preventDefault();
				var index = me.breadcrumbs.find('a').index($(this)),
					nextDir = me.breadcrumbsUrls[index];
				me.breadcrumbsUrls.length = parseInt(index);
				me.pathcontrol = nextDir;
				me.goto(nextDir);
			});
			this.goto('');
		},
		goto(hash) {
			var rendered = '';
			if (hash.trim().length) {
				rendered = this.searchByPath(hash);
				if (rendered.length) {
					this.breadcrumbsUrls = this.generateBreadcrumbs(hash);
					this.render(rendered);
				} else {
					this.breadcrumbsUrls = this.generateBreadcrumbs(hash);
					this.render(rendered);
				}

			} else {
				this.breadcrumbsUrls.push('ASCII');
				this.render(this.searchByPath('ASCII'));
			}

		},

		generateBreadcrumbs(nextDir) {
			var path = nextDir.split('/').slice(0);
			for (var i = 1; i < path.length; i++) {
				path[i] = path[i - 1] + '/' + path[i];
			}
			return path;
		},

		searchByPath(dir) {
			var path = dir.split('/'),
				demo = this.response,
				flag = 0;

			for (var i = 0; i < path.length; i++) {
				for (var j = 0; j < demo.length; j++) {
					if (demo[j].name === path[i]) {
						flag = 1;
						demo = demo[j].items;
						break;
					}
				}
			}
			demo = flag ? demo : [];
			return demo;
		},
		bytesToSize(bytes) {
			var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			if (bytes == 0) return '0 Bytes';
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		},
		escapeHTML(text) {
			return text.replace(/\&/g, '&amp;').replace(/\</g, '&lt;').replace(/\>/g, '&gt;');
		},
		funtionclick(e, name) { 

			window.location.href = '/download?file=' + e;
			swal({
				type: 'success',
				title: 'Se descargo el archivo correctamente',
				showConfirmButton: false,
				timer: 1500
			});
		},
		render(data) {
			console.log('valor data:',data);
			let me = this;
			var scannedFolders = [],
				scannedFiles = [];
			if (Array.isArray(data)) {
				data.forEach(function (d) {
					if (d.type === 'folder') {
						scannedFolders.push(d);
					} else if (d.type === 'file') {
						scannedFiles.push(d);
					}
				});
			}
			me.fileList.empty().hide();
			if (!scannedFolders.length && !scannedFiles.length) {
				this.filemanager.find('.nothingfound').show();
			} else {
				this.filemanager.find('.nothingfound').hide();
			}

			if (scannedFolders.length) {
				scannedFolders.forEach(function (f) {
					 
					var itemsLength = _.has(f,'mes')?f.items.length-1:f.items.length,
						name = me.escapeHTML(f.name),
						icon = '<span class="icon folder"></span>';
					if (itemsLength) {
						icon = '<span class="icon folder full"></span>';
					}
					if (itemsLength == 1) {
						itemsLength += ' item';
					} else if (itemsLength > 1) {
						itemsLength += ' items';
					} else {
						itemsLength = 'Vacio';
					}
					var folder = $('<li class="folders"><a href="' + f.path + '" title="' + name + '" class="folders">' + icon + '<span class="name">' + name + '</span> <span class="details">' + itemsLength + '</span></a></li>');
					folder.appendTo(me.fileList);
				});
			}

			if (scannedFiles.length) {
				scannedFiles.forEach(function (f) {
					var fileSize = me.bytesToSize(f.size),
						name = me.escapeHTML(f.name);
					 
					

					if(_.endsWith(name, 'json')){ 
					var icon = '<div class="col-md-3 row iconofile" style="text-align: center;margin: 0;padding: 0;"><span class="fa fa-file-pdf-o  filesize" style="color: red;"></span></div>';
					var file = $('<li class="files" style="border: 3px solid #ad0707f2;color: #ad0707f2;"><div  title="Generar PDF" class="row filefinal">' + icon + '<div class="col-md-9"><span class="name">Reporte Detallado</span> <span class="details">Generar PDF</span></div></div></li>');
					file.appendTo(me.fileList); 
					file.click(function() { 
						  axios.get('/getFile?file='+f.path)  
							.then(response => { 
								 
								_pl._vvp2521_00002(response.data,'viewerpdf');
								me.classModalpdf.openModal("generarpdf"); 
								 
							}).catch(error => {   
								console.log(error);
								swal(
									"¡ocurrio un problema al modificar los datos generados!",
									'',
									"error"
								);
							});  
						});

					var icon2 = '<div class="col-md-3 row iconofile" style="text-align: center;margin: 0;padding: 0;"><span class="fa fa-file-pdf-o  filesize" style="color: red;"></span></div>';
					var file2 = $('<li class="files" style="border: 3px solid #ad0707f2;color: #ad0707f2;"><div  title="Generar PDF" class="row filefinal">' + icon2 + '<div class="col-md-9"><span class="name">Reporte Resumido</span> <span class="details">Generar PDF</span></div></div></li>');
					file2.appendTo(me.fileList); 
					file2.click(function () {
						  axios.get('/getFile?file='+f.path)  
							.then(response => {  
								_pl._vvp2521_00003(response.data,'viewerpdf');
								me.classModalpdf.openModal("generarpdf");  
							}).catch(error => {   
								console.log(error);
								swal(
									"¡ocurrio un problema al modificar los datos generados!",
									'',
									"error"
								);
							}); 
					});

					}else{
					var icon = '<div class="col-md-3 row iconofile" style="text-align: center;margin: 0;padding: 0;"><span class="icon-doc icons  filesize"></span></div>';
					var file = $('<li class="files"><div  title="' + name + '" class="row filefinal">' + icon + '<div class="col-md-9"><span class="name">' + name + '</span> <span class="details">' + fileSize + '</span></div></div></li>');
					file.appendTo(me.fileList);
					$(file).toolbar({
						content: '#downloadFile',
						position: 'bottom',
						animation: 'standard',
						style: 'dark',
						event: 'click',
						hideOnClick: true,
						path: f.path,
						namefile: f.name,
						clickfunction: me.funtionclick
					});
					}

					
				});
			}

			var url = '';
			me.breadcrumbsUrls.forEach(function (u, i) {
				var name = u.split('/');
				if (i !== me.breadcrumbsUrls.length - 1) {
					url += '<a href="' + u + '"><span class="folderName">' + name[name.length - 1] + '</span></a> <span class="arrow">→</span> ';
				} else {
					url += '<span class="folderName">' + name[name.length - 1] + '</span>';
				}
			});
			me.breadcrumbs.text('').append(url);
			me.fileList.animate({
				opacity: "show"
			});

		},
		getfiles(){
			let me=this;
			axios.get('/view').then(function (response) {  
				 me.response=[];
				 me.response.push(response.data);
                 me.ini();
            })
            .catch(function (error) {
                console.log(error);
            });
		}
	},
	mounted() {
		this.getfiles();
		this.classModalpdf = new _pl.Modals();
        this.classModalpdf.addModal("generarpdf");
	}
}
</script>
<style> 
   
 
 

@media all and (max-width: 965px) {
	.filemanager {
		margin: 30px auto 0;
		padding: 1px;
	}
}
.filesize{
	margin-top: auto!important;
    margin-bottom: auto!important;
	width: 100%;
	font-size: 4.5rem !important;
}
 .filemanager{
margin: 15px;
 }
.filefinal{
	    margin: 0;
    width: 100%;
    height: 100%;
}

 .breadcrumbs {
	color: black;
	margin-left:20px;
	font-size: 15px;
	/* font-weight: 700; 
	line-height: 35px;*/
}

 .breadcrumbs a:link, .breadcrumbs a:visited {
	color: #20a8d8;
	text-decoration: none;
}

 .breadcrumbs a:hover {
	text-decoration: underline;
	color:black;
}

 .breadcrumbs .arrow {
	color:  #6a6a72;
	font-size: 20px;
	font-weight: 700;
	line-height: 20px;
}

 
 

/*-------------------------
	Content area
-------------------------*/

.filemanager .data { 
	 
    margin: 0;
    padding: 0;
}

.filemanager .data.animated {
	-webkit-animation: showSlowlyElement 700ms; /* Chrome, Safari, Opera */
	animation: showSlowlyElement 700ms; /* Standard syntax */
}

.filemanager .data li {
    border-radius: 7px;
    border: solid 1px;
    width: 314px;
    height: 98px;
    list-style-type: none;
    margin: 4px;
    display: inline-block;
    position: relative;
    overflow: hidden;
    /* padding: 0.3em; */
    z-index: 1;
    cursor: pointer;
    box-sizing: border-box;
    transition: 0.3s background-color;
}
.downloadFileclass:hover {
	cursor: pointer;
}
.filemanager .data li a:hover 
 {
	background-color: #42424E;
    color:white; 
}
.filemanager .data li div:hover 
 {
	background-color: #42424E;
    color:white; 
}
.filemanager .data li a {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}

.filemanager .data li .name { 
	font-size: 20px;
    font-weight: 700;
    line-height: 20px;
    /* width: 150px; */
    white-space: normal;
    display: inline-block;
    position: absolute;
    overflow: hidden;
    text-overflow: ellipsis;
    top: 20px;
}

.filemanager .data li .details {
 
	    font-size: 15px;
    /* font-weight: 400; */
    width: 109px;
    height: 20px;
    top: 50px;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
}

.filemanager .nothingfound {
	    /* background-color: #373743; */
    border: solid 1px #afbbc1;
    border-radius: 10px;
    width: 16em;
    height: 16em;
    margin: 0 auto;
    display: none;
    font-family: Arial;
    -webkit-animation: showSlowlyElement 700ms;
    animation: showSlowlyElement 700ms;
}

.filemanager .nothingfound .nofiles {
    margin: 8px auto;
    top: 1em;
    border-radius: 50%;
    position: relative;
    background-color: #c71527b0;
    width: 9em;
    height: 9em;
    line-height: 9.4em;
}
.filemanager .nothingfound .nofiles:after {
    content: '\D7';
    position: absolute;
    color: #ffffff;
    font-size: 9em;
    margin-right: 0.195em;
    right: 0px;
}

.filemanager .nothingfound span {
    margin: 0px auto auto 2.3em;
    color: #29363d;
    font-size: 17px;
    /* font-weight: 700; */
    line-height: 18px;
    height: 16px;
    position: relative;
    top: 1.5em;
}
.iconofile {
	display: flex;
	}

@media all and (max-width:965px) {

	.filemanager .data li {
		width: 100%;
		margin: 5px 0;
	}
	.iconofile {
		display: none;
	}
} 

/* Chrome, Safari, Opera */
@-webkit-keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

/* Standard syntax */
@keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}


/*-------------------------
		Icons
-------------------------*/

.icon {
	font-size: 23px;
}
.icon.folder {
	display: inline-block;
	margin: 1em;
	background-color: transparent;
	overflow: hidden;
}
.icon.folder:before {
	content: '';
	float: left;
	background-color: #7ba1ad;

	width: 1.5em;
	height: 0.45em;

	margin-left: 0.07em;
	margin-bottom: -0.07em;

	border-top-left-radius: 0.1em;
	border-top-right-radius: 0.1em;

	box-shadow: 1.25em 0.25em 0 0em #7ba1ad;
}
.icon.folder:after {
	content: '';
	float: left;
	clear: left;

	background-color: #a0d4e4;
	width: 3em;
	height: 2.25em;

	border-radius: 0.1em;
}
.icon.folder.full:before {
	height: 0.55em;
}
.icon.folder.full:after {
	height: 2.15em;
	box-shadow: 0 -0.12em 0 0 #ffffff;
}
 

::-webkit-scrollbar {
  width: 6px;
  border-radius: 10px;
}
::-webkit-scrollbar-track {
  background: transparent;  
}
::-webkit-scrollbar-thumb {
  background: #2186aab0; 
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #276f8a; 
}
</style>
