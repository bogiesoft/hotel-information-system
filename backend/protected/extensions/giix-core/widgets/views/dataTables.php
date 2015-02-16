
		<section class="panel">
			<?php
			if($dataProvider == NULL){
				echo "No Data";
			}else{
			?>
			<table id="mydatatables" class="table table-bordered table-stripped">
				<thead>
					<tr>
						<?php
						$labels = $dataProvider[0]->attributeLabels();
						foreach($labels as $key => $value){
							echo "<th>".$value."</th>";
						}
						?>
						<th>#</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($dataProvider as $data){
						echo "<tr>";
						$labels = $data->getAttributes();
						foreach($labels as $key => $value){
							echo "<td>".$value."</td>";
						}
						?>
							<td>
								<a href="<?php echo Yii::app()->request->baseUrl; ?>/<?php echo Yii::app()->controller->id; ?>/update/<?php echo $data->primaryKey; ?>" class="btn btn-danger">
									<i class="icon-edit"></i>
									Edit
								</a>
							</td>
						</tr>
						<?php
					}
					
					?>
				</tbody>
			</table>
			<?php } ?>
			<script>
				$(document).ready(function(){
					$("#mydatatables").dataTable();	
				});
			</script>
		</section>