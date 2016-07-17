<?php

class paginate
{
     private $db;
 
     function __construct($DB_con)
     {
         $this->db = $DB_con;
     }
 
     public function dataview($query,$user_id)
     {
		 $now = date("Y-m-d");
         $stmt = $this->db->prepare($query);
         $stmt->execute(array($now,$user_id));
 
         if($stmt->rowCount()>0)
         {
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                   ?>
					<div id="content-left"><?php echo $row['ride_id']; ?></div>
					<div id="content-main"><?php echo $row['location_from']; ?></div>
					<div id="content-main"><?php echo $row['location_to']; ?></div>
					<div id="content-main"><?php echo $row['date']; ?></div>
					<div id="content-main"><?php echo $row['time_hours']; $row['time_minutes']; $row['time_period']; ?></div>
					<div id="content-right"><?php echo $row['available_rides']; ?></div>
                   <?php
                }
         }
         else
         {
                ?>
                <tr>
                <td>Nothing here...</td>
                </tr>
                <?php
         }
  
 }
 
 public function paging($query,$records_per_page)
 {
        $starting_position=0;
        if(isset($_GET["page_no"]))
        {
             $starting_position=($_GET["page_no"]-1)*$records_per_page;
        }
        $query2=$query." limit $starting_position,$records_per_page";
        return $query2;
 }
 
 public function paginglink($query,$records_per_page,$user_id)
 {
		$now = date("Y-m-d");
        $self = $_SERVER['PHP_SELF'];
  
        $stmt = $this->db->prepare($query);
        $stmt->execute(array($now,$user_id));
  
        $total_no_of_records = $stmt->rowCount();
  
        if($total_no_of_records > 0)
        {
            
            $total_no_of_pages=ceil($total_no_of_records/$records_per_page);
            $current_page=1;
            if(isset($_GET["page_no"]))
            {
               $current_page=$_GET["page_no"];
            }
            if($current_page!=1)
            {
               $previous =$current_page-1;
               echo "<a href='".$self."?page_no=1'>First</a>&nbsp;&nbsp;";
               echo "<a href='".$self."?page_no=".$previous."'>Previous</a>&nbsp;&nbsp;";
            }
            for($i=1;$i<=$total_no_of_pages;$i++)
            {
            if($i==$current_page)
            {
                echo "<strong><a href='".$self."?page_no=".$i."' style='color:red;text-decoration:none'>".$i."</a></strong>&nbsp;&nbsp;";
            }
            else
            {
                echo "<a href='".$self."?page_no=".$i."'>".$i."</a>&nbsp;&nbsp;";
            }
   }
   if($current_page!=$total_no_of_pages)
   {
        $next=$current_page+1;
        echo "<a href='".$self."?page_no=".$next."'>Next</a>&nbsp;&nbsp;";
        echo "<a href='".$self."?page_no=".$total_no_of_pages."'>Last</a>&nbsp;&nbsp;";
   }
  }
 }
}