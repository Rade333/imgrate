$(document).ready(function() {

  $('input#file').change(function() {
    $('input#fileText').val(this.files && this.files.length ? this.files[0].name : this.value.replace(/^C:\\fakepath\\/i, ''));
  });

});

