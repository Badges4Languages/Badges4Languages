jQuery(document).ready(function($){
    
    $('#badge-student-self-certification').toggle('hide');
    $('#badge-student-awarded-by-teacher').toggle('hide');
    $('#badge-teacher-self-certification').toggle('hide');
    
    $('#titre-badge-student-self-certification').click(function() {
         $('#badge-student-self-certification').toggle('show');
    });
    $('#titre-badge-student-awarded-by-teacher').click(function() {
         $('#badge-student-awarded-by-teacher').toggle('show');
    });
    $('#titre-badge-teacher-self-certification').click(function() {
         $('#badge-teacher-self-certification').toggle('show');
    });
});