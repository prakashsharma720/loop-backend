<?php /*
 * Dynmic_menu.php
 */

class User_rights_url {
 
   private $ci;            // para CodeIgniter Super Global Referencias o variables globales
    //private $nav_class        = 'class="mt-2"';
 
    function __construct()
    {
        $this->ci =& get_instance();    // get a reference to CodeIgniter.
    }
        function getUrlList($role_id,$employee_id){
           
            //$ci->session->userdata('user_authen');
            //$role_id=$this->ci->session->userdata['logged_in']['role_id'];
            //echo $login_id;exit;
            $userrights = $this->ci->db->query("select * from user_rights where role_id=$role_id");
            $userData=$userrights->result();
            $userRightsIds2=[];
            $userRightsIds2=explode(',', $userData[0]->menu_ids);
            //print_r($userRightsIds2);
            $userrights1 = $this->ci->db->query("select * from user_rights where employee_id=$employee_id");
            $userData1=$userrights1->result();
            $userRightsIds1=[];
            if(!empty($userData1)){
                 $userRightsIds1=explode(',', $userData1[0]->menu_ids);
            }
            
           
            //print_r($userRightsIds1);
            $userRightsIds=array_merge($userRightsIds2,$userRightsIds1);
            $menuUrl = [];
            //print_r($userRightsIds);exit;
            foreach ($userRightsIds as $key => $menu_id) {
               // echo $menu_id;
                $query = $this->ci->db->query("select url,controller,action from menus where id='$menu_id' ");
                $userData = $query->result_array();
                //print_r($userData);exit;
                
                //$userData=$query->result_array();
                //$menuUrl[]=$userData['url'];
                $menuUrl[]=$userData[0]['controller'].'/'.$userData[0]['action'];
               // $menuUrl[]='/'.$userData[0]['controller'].'/'.$userData[0]['action'];

            }
            return $menuUrl;

        }
       


    // me despliega del query los rows de la base de datos que deseo utilizar
        //print_r($query->result());exit;
   
}
 
// ------------------------------------------------------------------------
// End of Dynamic_menu Library Class.
// ------------------------------------------------------------------------
/* End of file Dynamic_menu.php */
/* Location: ../application/libraries/Dynamic_menu.php */

?>