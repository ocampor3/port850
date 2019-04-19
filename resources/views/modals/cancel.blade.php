
<!-- ########################### Initialize Modal Window for confirmation ########################-->
<div class="modal fade" id="confirmCancel" tabindex="-1" role="dialog" aria-labelledby="confirmCancelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">Cancel</h3>
            </div> <!-- End Modal Header -->
            <div class="modal-body">
                <p>Your changes will not be saved. Are you sure? </p>
            </div> <!-- End Modal Body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm"><x>Confirm</x></button>
            </div> <!-- End Modal Footer -->
        </div> <!-- End Modal Content -->
    </div> <!-- End Modal Dialog -->
</div> <!-- End Modal -->


	<!-- Javascript functions to control modal data injection -->
<script type="text/javascript">
    $href = "";
    
    $('#confirmCancel').on('show.bs.modal', function (e) {
      $message = $(e.relatedTarget).attr('data-message');
      $(this).find('.modal-body p').text($message);
      $title = $(e.relatedTarget).attr('data-title');
      $(this).find('.modal-title').text($title);

      // Grab custom settings from submission
      // Button Text for action button
      $btntxt = $(e.relatedTarget).attr('data-btntxt');
      $(this).find('.modal-footer x').text($btntxt);
      // Cancel Button Class
      $btncan = $(e.relatedTarget).attr('data-btncancel');
      // Primary Action Button Class
      $btnac = $(e.relatedTarget).attr('data-btnaction');
        
      $href = $(e.relatedTarget).attr('data-href');
      
    });

    //-- Form confirm (yes/ok) handler, submits form --//
    $('#confirmCancel').find('.modal-footer #confirm').on('click', function(e){ 
        //$(this).data('form').submit();  
        window.open($href, '_self');        
    });
</script>