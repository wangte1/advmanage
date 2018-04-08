diff --git a/applications/admin/application/controllers/Housesassign.php b/applications/admin/application/controllers/Housesassign.php
index 711cf2c..512c283 100755
--- a/applications/admin/application/controllers/Housesassign.php
+++ b/applications/admin/application/controllers/Housesassign.php
@@ -110,7 +110,6 @@ class Housesassign extends MY_Controller{
     	
     	if(IS_POST){
     		$post_data = $this->input->post();
-    		
     		$order_id = $this->input->post('order_id');
     		$houses_ids = $this->input->post('houses_id');
     		$points_counts = $this->input->post('points_count');
diff --git a/applications/admin/application/views/housesassign/assign.php b/applications/admin/application/views/housesassign/assign.php
index a543c7b..bcebf20 100755
--- a/applications/admin/application/views/housesassign/assign.php
+++ b/applications/admin/application/views/housesassign/assign.php
@@ -92,10 +92,10 @@
 				<?php }?>
 			</tbody>
 		</table>
+		<div style="height: 50px;"></div>
 	</div>
-	
 	<center>
-		<button class="btn btn-sm btn-info sub-button" type="button">保存并通知</button>
+		<button style="position: fixed;" class="sub-button" type="button">保存并通知</button>
 	</center>
 </form>
 </div>
@@ -124,7 +124,7 @@ $(function(){
 			  shade: 0.6,
 			  area: ['90%', '90%'],
 			  content: '/housesassign/show_ban?order_id='+order_id+'&houses_id='+houses_id+'&charge_id_str='+charge_id_str+'$remark_str='+remark_str //iframe的url
-			}); 
+		}); 
 	});
 
 	$('.charge-sel').change(function(){
diff --git a/applications/admin/application/views/housesassign/index.php b/applications/admin/application/views/housesassign/index.php
index e1cae4a..596f806 100755
--- a/applications/admin/application/views/housesassign/index.php
+++ b/applications/admin/application/views/housesassign/index.php
@@ -331,7 +331,7 @@
 				  title: '派单',
 				  shadeClose: true,
 				  shade: 0.6,
-				  area: ['70%', '70%'],
+				  area: ['70%', '72%'],
 				  content: 'housesassign/assign?order_id='+order_id+'&assign_type='+assign_type //iframe的url
 				}); 
 		});
