
<?php

/*
===========================================
=======    Category Page 	   ========
=======    Manage - (Add - Insert - Activate ) - ( Edit - Update ) - Delete     ========
===========================================

*/
	ob_start();        // output Buffering Start
	session_start();	// Start Session 
	$pageTitle = "Categories";	// Page Title

	if (isset($_SESSION['username'])) {
		include 'init.php';
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		
		if ($do == 'Manage') {
			$sort_Array = array('ASC','DESC');
			$sort = 'ASC';
			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_Array)) {
						$sort =$_GET['sort'];
			}

			$stmt = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");
			$stmt->execute();
			$result = $stmt->fetchAll();
?>
			<h1 class="text-center">Manage Categories</h1>
			<div class="container categories">
				<?php 
				if(!empty($result)){
				?>
				<div class="panel panel-default">
					<div class="panel-heading"><i class='far fa-edit fa-lg'></i>Manage Categories
						<div class="cat-option pull-right">
							<strong><i class="fas fa-sort"></i>Ordering:[</strong> 
							<a class="<?php if ($sort == 'ASC') { echo "active"; } ?>" href="categories.php?do=Manage&sort=ASC">
								<i class="fas fa-sort-amount-down"></i>ASC </a> <strong>|</strong>
							<a class="<?php if ($sort == 'DESC') { echo "active"; } ?>" href="categories.php?do=Manage&sort=DESC">
							<i class="fas fa-sort-amount-up"></i>DESC</a><strong> ]</strong> 
							<strong><i class="fas fa-eye"></i>View:[</strong>
							<span class="active" data-view='full'>Full</span><strong> |</strong>							
							<span data-view='classic'>Classic</span><strong> ]</strong>
						</div>
					</div>
					<div class="panel-body">
						<?php
							foreach ($result as $cat) {

								// check IF Category don't have Description 
								if($cat['Description'] == ''){
									$des = 'This Category haven\'t a Description';
								} else {
									$des = $cat['Description'];
								}


								echo "<div class='cat'>";
									echo "<div class='hidden-controles'>
											<a class='btn btn-info' href='categories.php?do=Edit&catid=" . $cat['ID'] . "'>
												<i class='far fa-edit fa-xs'></i>
												Edit
											</a>
											<a class='btn btn-danger confirm' href='categories.php?do=Delete&catid=" . $cat['ID'] . "'>
												<i class='far fa-times-square fa-xs'></i>
												Delete
											</a>
									</div>";
									echo "<h3>"   . $cat['Name']         . "</h3>";
									echo "<div class='full-view'>";
										echo "<p>"    .$des                  . "</p>";

																	// Get the Child Categories
					      		
					      		$childcats = getAll("*","categories",'WHERE Parent ='.$cat["ID"],"ORDER BY ID DESC");
					      		if (!empty($childcats)) {
					      			echo "<h4>Sub Categories</h4>";
					      			echo "<ul class='list-unstyled'>";
						      			foreach ($childcats as $childcat) {
						      				echo "<div class='sub-cat'><i class='fas fa-angle-double-right fa-fw'></i>";
											echo "<li><a href='categories.php?do=Edit&catid=".$childcat['ID']."'>".$childcat['Name']."</a></li>";
											echo "<a class='delete-sub-cat confirm  btn btn-danger' href='categories.php?do=Delete&catid=" . $childcat['ID'] . "'>Delete</a></div>";
										}
									echo "</ul>";
					      		}

								// End Child Cats

										if($cat['Visibility'] == 0 ){ echo "<span class='visibility'>
										<i class='fas fa-eye-slash'></i>Hide</span>";}
										if($cat['AllowComment']== 0) { echo "<span class='commenting'>
										<i class='fas fa-comment-slash'></i>Block Com</span>";}
										if ($cat['AllowAds']== 0) {echo "<span class='advertises'>
										<i class='far fa-window-close'></i>Block Ads</span>";}
									echo "</div>";

								echo "</div>";
								echo "<hr>";



							}
						?>
					</div>
				</div>
			<?php } else {
							echo "<div class='alert alert-danger text-center'>There is No Categories Added Yet !, You Can Add New One from the down button</div>";
					}

			?>
				<a class="btn btn-primary btn-lg add-category" href='categories.php?do=Add'><i class="fas fa-plus-square"></i>New Category </a>
			</div>

<?php
		}elseif ($do == 'Add'){
		?>

<!-------------------- Add New Category Form -------------------------->

		<h3 class="text-center update-head">Add New Category</h3>
		<div class="container">
			<form class="form-horizontal" action="?do=Insert" method="POST">
				<!--   Category Name   -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Category Name</label>
					<div class="col-sm-10 col-md-4">
						<input type="text" name="name" class="form-control" autocomplete="off" required placeholder="Category Name" >
					</div>
				</div>

				<!--   Description   -->					
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10 col-md-4">
						<input type="textarea" name="description" class="form-control"  placeholder="Descripe The Category">
					</div>
				</div>

				<!--   Ordering   -->					
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Ordering</label>
					<div class="col-sm-10 col-md-4">
						<input type="text" name="ordering" class="form-control"  placeholder="Categpry Ordering" >
					</div>					
				</div>


				<!--   Ordering   -->					
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Parent</label>
					<div class="col-sm-10 col-md-4">
						<select name="parent">
							<option value="0">None</option>
							<?php 
								$allcats = getAll("*","categories","WHERE Parent = 0","ORDER BY ID ASC");
								foreach ($allcats as $cat) {
									echo "<option value='".$cat['ID']."''>".$cat['Name']."</option>";
								}
							?>
						</select>
					</div>					
				</div>

				<!--   Visibility   -->				
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Visibile</label>
					<div class="col-sm-10 col-md-4">
						<div>
							<input id="vis-yes" type="radio" name="visibility" value="0" checked>
							<label for="vis-yes">Yes</label>
						</div>
						<div>
							<input id="vis-no" type="radio" name="visibility" value="1">
							<label for="vis-no">No</label>
						</div>
					</div>
				</div>

				<!--   Comments   -->				
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Allow Commenting for This Category</label>
					<div class="col-sm-10 col-md-4">
						<div>
							<input id="com-yes" type="radio" name="commenting" value="0" checked>
							<label for="com-yes">Yes</label>
						</div>
						<div>
							<input id="com-no" type="radio" name="commenting" value="1">
							<label for="com-no">No</label>
						</div>
					</div>
				</div>

				<!--   Ads   -->				
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Allow Ads</label>
					<div class="col-sm-10 col-md-4">
						<div>
							<input id="ads-yes" type="radio" name="ads" value="0" checked>
							<label for="ads-yes">Yes</label>
						</div>
						<div>
							<input id="ads-no" type="radio" name="ads" value="1">
							<label for="ads-no">No</label>
						</div>
					</div>
				</div>

				<!--   Submit   -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-4">
						<input type="submit" Value="Add" class="btn btn-primary btn-block btn-lg">
					</div>
				</div>
			</form>
		</div>

		<?php	

		} elseif ($do == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Add New Category</h1>";
				echo "<div class='container'>";
				$name  = $_POST['name'];
				$desc  = $_POST['description'];
				$parent = $_POST['parent'];
				$order = $_POST['ordering'];
				$visible = $_POST['visibility'];
				$comment = $_POST['commenting'];
				$ads = $_POST['ads'];

				/* Form Validation */
				if (empty($name)) {
						errorRedirect("You Must Type Category Name",'back');					
				}elseif (strlen($name) < 4 || strlen($name) > 20 ) {
					errorRedirect("Category Name Must Contains from 4 to 20 chars or special Chars",'back');
				}else {

					if (!checkexist("Name","categories",$name)){

					$stmt = $con->prepare("Insert INTO categories (Name, Description, Parent, Ordering, Visibility, AllowComment, AllowAds) VALUES (:xname, :xdesc, :xparent, :xorder, :xvisible, :xcomment, :xads) ");
					$stmt->execute(array(
						'xname' 	=> $name,
						'xdesc' 	=> $desc,
						'xparent'	=> $parent, 
						'xorder' 	=> $order, 
						'xvisible' 	=> $visible,
						'xcomment'	=> $comment, 
						'xads'  	=> $ads));
					$count = $stmt->rowCount();

					//echo "<div class='alert alert-success text-center'>" .  . " </div>";
					successRedirect("New Category Added Successfuly",'categories.php');

					} else {
						errorRedirect("This Category Already Exists",'back');
					}
				} 

			} else {
				errorRedirect("sorry you cann't browse this page Directly",'back');
			}
			echo "</div>";
		} 
		elseif ($do == 'Edit') {
					if (isset($_GET['catid']) && is_numeric($_GET['catid']) && $_GET['catid'] > 0 ) { 

					$catid = intval($_GET['catid']);
					$stmt = $con->prepare("select * FROM categories WHERE ID = ?");
					$stmt->execute(array($catid));
					$count = $stmt->rowCount();

					if ($count > 0) {

						$editcat = $stmt->fetch(); 	
	?>
	<!-------------------- Edit Category Form -------------------------->

		<h3 class="text-center update-head">Edit Category</h3>
		<div class="container">
			<form class="form-horizontal" action="?do=Update" method="POST">
				<!--   Category Name   -->
				<input type="hidden" name="catid" value="<?php echo $editcat['ID']?>">
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Category Name</label>
					<div class="col-sm-10 col-md-4">
						<input type="text" name="name" class="form-control" autocomplete="off" required placeholder="Category Name" value="<?php echo $editcat['Name']?>" >
					</div>
				</div>

				<!--   Description   -->					
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10 col-md-4">
						<input type="textarea" name="description" class="form-control"  placeholder="Descripe The Category" value="<?php echo $editcat['Description']?>" >
					</div>
				</div>

				<!--   Ordering   -->					
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Ordering</label>
					<div class="col-sm-10 col-md-4">
						<input type="text" name="ordering" class="form-control"  placeholder="Categpry Ordering" value="<?php echo $editcat['Ordering']?>">
					</div>					
				</div>


				<!--   Ordering   -->					
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Parent</label>
					<div class="col-sm-10 col-md-4">
						<select name="parent">
							<option value="0">None</option>
							<?php 
								$allcats = getAll("*","categories","WHERE Parent = 0","ORDER BY ID ASC");
								foreach ($allcats as $cat) {
									echo "<option value='".$cat['ID']."'";
										if($cat['ID'] == $editcat['Parent']){ echo "selected";}
									echo ">".$cat['Name']."</option>";
								}
							?>
						</select>
					</div>					
				</div>

				<!--   Visibility   -->				
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Visibile</label>
					<div class="col-sm-10 col-md-4">
						<div>
							<input id="vis-yes" type="radio" name="visibility" value="0" 
								<?php if($editcat['Visibility'] == 0 ){ echo "checked"; }?>
							>							
							<label for="vis-yes">Yes</label>
						</div>
						<div>
							<input id="vis-no" type="radio" name="visibility" value="1"
								<?php if($editcat['Visibility'] == 1 ){ echo "checked"; }?>
							>
							<label for="vis-no">No</label>
						</div>
					</div>
				</div>

				<!--   Comments   -->				
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Allow Commenting for This Category</label>
					<div class="col-sm-10 col-md-4">
						<div>
							<input id="com-yes" type="radio" name="commenting" value="0" 
								<?php if($editcat['AllowComment'] == 0 ){ echo "checked"; }?>
							>
							<label for="com-yes">Yes</label>
						</div>
						<div>
							<input id="com-no" type="radio" name="commenting" value="1" 
								<?php if($editcat['AllowComment'] == 1 ){ echo "checked"; }?>
							>
							<label for="com-no">No</label>
						</div>
					</div>
				</div>

				<!--   Ads   -->				
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Allow Ads</label>
					<div class="col-sm-10 col-md-4">
						<div>
							<input id="ads-yes" type="radio" name="ads" value="0" 
								<?php if($editcat['AllowAds'] == 0 ){ echo "checked"; }?>
							>
							<label for="ads-yes">Yes</label>
						</div>
						<div>
							<input id="ads-no" type="radio" name="ads" value="1"
								<?php if($editcat['AllowAds'] == 1 ){ echo "checked"; }?>
							>							
							<label for="ads-no">No</label>
						</div>
					</div>
				</div>

				<!--   Submit   -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-4">
						<input type="submit" Value="Save" class="btn btn-primary btn-block btn-lg">
					</div>
				</div>
			</form>
		</div>

	<?php
				} else {
				errorRedirect("This Category is not Exist ",'back');
			}
	} else {
	errorRedirect("Invalid Category ID",'back');
	}					
		} 
		elseif ($do == 'Update') {

			echo "<h1 class='text-center'>Update The Category</h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id    = $_POST['catid'];
				$name  = $_POST['name'];
				$desc  = $_POST['description'];
				$parent = $_POST['parent'];
				$order = $_POST['ordering'];
				$visible = $_POST['visibility'];
				$comment = $_POST['commenting'];
				$ads = $_POST['ads'];

				/*
				$id    = $_POST['catid'];
				$catname  = $_POST['name'];
				$description = $_POST['description'];
				$ordering = $_POST['ordering'];
				$parent  = $_POST['parent'];
				$visibility = $_POST['visibility'];
				$commenting = $_POST['commenting'];
				$ads = $_POST['ads'];
				*/
				/* Form Validation */
				$errors = array();
				if (empty($name)) {
					$errors[] = "You Must Type The Category Name";
				}
				if (empty($desc)) {
					$errors[] = "You Must Type Category Description";
				}
				if (empty($order)) {
					$errors[] = "You Must Type Category Ordering";
				}
				if(empty($errors)){

					$stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Parent = ?, Ordering = ?, Visibility = ?, AllowComment = ?, AllowAds = ?  WHERE ID = ?");
					$stmt->execute(array($name, $desc, $parent, $order, $visible, $comment, $ads, $id));
					//echo "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " Record Updated</div>";
					successRedirect("Category Data Updated Successfuly",'categories.php');

				}else {
					foreach ($errors as $error) {
						//echo  "<div class='alert alert-danger text-center'>" . $error."</div>";
						errorRedirect($error,'back');
					}
				}
			} else {
				errorRedirect("sorry you cann't browse this page Directly",'back');
			}
			echo "</div>";


		} 
		elseif ($do == 'Delete') {
			echo "<h1 class='text-center'>Delete Category</h1>";
			echo "<div class='container'>";	
				if (isset($_GET['catid']) && is_numeric($_GET['catid']) && $_GET['catid'] > 0 ) { 
					
						$categoryid = intval($_GET['catid']);
						if (checkexist("ID","categories",$categoryid)){			
							$stmt = $con->prepare("DELETE FROM categories WHERE ID = ?");
							$stmt->bindParam("zuserid",$_GET['catid']);
							$stmt->execute(array($categoryid));
							successRedirect("Category Deleted Successfuly","categories.php");
					
						} else {
							errorRedirect("This Category Not Exist in DB",'back');
						}

				} else {
							errorRedirect("Invalid Parameters",'back');
				}
			echo "</div>";
		} 

		include $tmp."footer.php";
	} 
	else {
		header("location:index.php");
		exit();
	}
	ob_end_flush();