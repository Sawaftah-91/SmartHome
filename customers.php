<?php include('includes/navbar.php'); ?>
<?php show_messages(); ?>
<div class="row g-2 mb-2">
    <div class="card mb-1">
        <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
        </div>
        <!--/.bg-holder-->
        <div class="card-body position-relative">
          <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary btn-sm me-1 mb-1 add_click" data-bs-toggle="modal" data-bs-target="#customer_modal" type="button">
              <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>
              <span class="lang" data-key="add"></span>
            </button>

          </div>
            <div class="row">
            <div class="col-lg">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-hover datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="lang" data-key="full_name"></th>
                                <th class="lang" data-key="phone"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php 
                                $i=1;
                                $row=get_customers();
                                foreach($row as $value){?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->customer_name ?></td>
                                <td><?php echo $value->phone_number ?></td>
                                <td class="text-end">
                                  <div class="d-flex justify-content-end gap-2">
                                    <button class="btn p-0 edit_click" type="button" data-bs-toggle="modal" id="<?php echo $value->id; ?>" data-bs-target="#customer_modal">
                                      <span class="text-500 fas fa-edit"></span>
                                    </button>
                                    <button class="btn p-0 del_click del_section" type="button" data-bs-toggle="modal" id="<?php echo $value->id; ?>" table="customers" data-bs-target="#delete">
                                      <span class="text-500 fas fa-trash-alt"></span>
                                    </button>
                                  </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th class="lang" data-key="full_name"></th>
                                <th class="lang" data-key="phone"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row g-0">
  
</div>
<div class="row g-0">
  
</div>
<div class="row g-0">
  
</div>
<div class="row g-0">
</div>

  <script>
      $(document).on("click", ".edit_click", function () {
    var id = $(this).attr("id");
    $.ajax({
      url: "includes/fetch_record_data.php",
      method: "POST",
      data: {
        id: id,
        type: "customers",
      },
      dataType: "json",
      success: function (data) {
        $("#customer_name").val(data.customer_name);
        $("#customer_phone").val(data.phone_number);
        $(".primary_id").val(id);
      },
    });
  });
  </script>
<?php include('includes/footer.php'); ?>


