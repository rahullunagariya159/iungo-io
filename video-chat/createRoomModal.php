<!-- Start Modal for create new room  -->


				<div class="modal fade" id="createNewRoom2" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLabelRoom">Create New Room</h5>
				        <button type="button" class="close venueModelClose" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form action="" name="createRoom_form" method="POST" id="venueNewFrm" enctype="multipart/form-data">
				        	<input type="hidden" name="venueAction" id="venueAction" value="add" />
				        	<input type="hidden" name="venueId" id="venueId" value="0" />
				        	<div class="row">
				        	<div class="col-12">
							  <div class="form-group">
							    <label for="lblVenueName">Venue name</label>
							    <input type="text" class="form-control" id="venueName" name="venueName" placeholder="Enter a venue name" required="">
							  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
						  <div class="form-group">
						    <label for="lblVenueDesc">Venue description</label>
						    <textarea class="form-control" id="venueDesc" name="venueDesc" rows="3"  required="required"></textarea>
						  </div>
						</div>
						</div>

						  		<div class="row">
						  			<div class="col-6">
						  				<label for="lblVanueType">Venue Type</label>
						  			</div>
						  		</div>

						  	<div class="row">
						 		 <div class="form-check">

								  		<div class="col-12">
									  <input class="form-check-input" type="radio" name="venueType" id="venueTypePub" value="public" checked >
									  <label class="form-check-label" for="rdbVPub">
									    Public &nbsp; <i class="fa fa-eye vTypE" data-toggle="tooltip" data-placement="top" title="Everybody in the Space can join your Venue"></i>
									  </label>

									</div>
									</div>
									<div class="spinner-border text-primary spindMod" role="status" style="display: none;">
									  <span class="sr-only">Loading...</span>
									</div>
									&nbsp;
									<!-- <div class="col-8"> -->
									  <div class="form-check privateRbt" >
										  <input class="form-check-input" type="radio" name="venueType" id="venueTypePriv" value="private">
											  <label class="form-check-label" for="rdbVPriv">
											    Private &nbsp; <i class="fa fa-eye vTypE"  data-toggle="tooltip" data-placement="top" title="The Venue’s moderators needs to accept new members in this Venue."></i>
											  </label>
										</div>
									<!-- </div> -->
								
									
							</div>

						  <!-- <div class="form-group">
						    <label for="formGroupExampleInput2">Another label</label>
						    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input placeholder">
						  </div> -->
						  <div class="row moderatorsLst" style="display: none">
						  	<div class="col-12">
							  	<select class="js-example-basic-multiple col-12" name="moderators[]" multiple="multiple">
								
								</select>
							</div>
						</div>
						<div class="row venueImgDiv">
							<div class="col-6">
								<div class="form-group">
							    <label for="exampleFormControlFile1">Venue picture</label>
							    <input type="file" class="form-control-file" id="venuePicture" name="venuePicture">
							  </div>
							</div>
							<div class="col-4">
									<img src="" style="display: none" class="venueImg" />

							</div>
						</div>
						
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-secondary venueModelClose" data-dismiss="modal">Close</button>
				        <input type="submit" class="btn btn-primary" name="createRoom" id="roomCreate" value="Create Room">
				        <!-- <button type="submit" class="btn btn-primary" name=""></button> -->
				      </div>
				      </form>
				    </div>
				  </div>
				</div>


  

  				 <!-- end Modal for create new room  -->