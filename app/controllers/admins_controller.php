<?php
class AdminsController extends AppController {

  var $name = 'Admins';
  var $uses = array('Admin');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck', 'Js');
  var $components = array('RequestHandler');

  function change_password() {
		$adminData = $this->Session->read('adminData');
		if(empty($adminData))
		$this->redirect('/admins/login');

		$this->layout = "";
		$this->set('pageTitle', 'Change Password');
		if(!empty($this->params['form'])) {
			
			$text_password = $this->params['form']['old_password'];
      $old_password = md5($this->params['form']['old_password']);
			$new_password = md5($this->params['form']['new_password']);
			$user_id = $this->Session->read('adminData.Admin.id');
			
			$checkPassword = $this->Admin->find('count', array('conditions'=>array('Admin.password'=> $old_password, 'Admin.id' => $user_id)));
			if($checkPassword > 0){
				$this->data['Admin']['id'] = $user_id;
				$this->data['Admin']['password'] = $new_password;
				if($this->Admin->save($this->data)){
					$this->Session->setFlash('Password changed successfully!');
					$this->redirect('/admins/change_password');
				}else{
					$this->Session->setFlash('Error Occured!');
					$this->redirect('/admins/change_password');
				}
			}else{
				$this->Session->setFlash('Incorrect old password. Please try again');
				$this->redirect('/admins/change_password');
			}
		}
	}
  
  function createRandomId($pre_code) {
    $pre_code = (strlen($pre_code) == 1) ? "0" . $pre_code : $pre_code;
    $pre_code = (strlen($pre_code) == 2) ? "" . $pre_code : $pre_code;
    $gen_code = $pre_code . rand(100000, 999999);

    if (strlen($pre_code) == 1)
      $gen_code = rand(100000, 999999);
    if (strlen($pre_code) == 2)
      $gen_code = rand(100000, 999999);
    if (strlen($pre_code) == 3)
      $gen_code = rand(10000, 99999);

    $gen_code = $pre_code . $gen_code;
    return $gen_code;
  }

  function login() {
    $this->layout = "";
    if (!empty($this->params['form'])) {
      $this->data['Admin'] = $this->params['form'];
      $condn = array(
          'conditions' => array('Admin.username' => $this->data['Admin']['username'], 'Admin.password' => md5($this->data['Admin']['password']), 'Admin.status' => '1')
      );

      $adminCount = $this->Admin->find('count', $condn);
      if ($adminCount > 0) {
        $adminData = $this->Admin->find('first', $condn);
        $this->Session->write('adminData', $adminData);
        $this->Session->write('admin_type',$adminData['Admin']['type']);
        $this->Admin->id = $adminData['Admin']['id'];
       
        $this->Admin->saveField('last_login', date('Y-m-d h:i:s'));
        $this->Admin->saveField('last_login_ip', $this->RequestHandler->getClientIP());
        
        $this->redirect('/dashboard');
      } else {
        $this->Session->setFlash('Incorrect username or password. Please try again');
        // $this->redirect(array('controller' => 'admins', 'action' => 'login'));
        $this->redirect('/admins/login');
      }
    }
  }

  function logout() {
    $this->Session->delete('adminData');
    $this->Session->destroy();
    $this->redirect('/admins/login');
  }

  function filterQueryForGrid($filters, $modelName) {
    // debugbreak();   
    //if($_SERVER['REMOTE_ADDR']=='10.1.31.77'){debugbreak();}    
    // GridFilters sends filters as an Array if not json encoded
    if (is_array($filters)) {
      $encoded = false;
    } else {
      $encoded = true;
      $filters = json_decode($filters);
    }

    // initialize variables
    $where = ' 0 = 0 ';
    $qs = '';


    // loop through filters sent by client
    if (is_array($filters)) {
      for ($i = 0; $i < count($filters); $i++) {
        $filter = $filters[$i];
        // echo $filter['field']; exit();
        if ($filter['field'] == 'branch') {
          $data = $filter['data']['value'];
          $cond = " Branch.name LIKE '%" . $data . "%'";
          $arr = $this->Branch->find('all', array('conditions' => $cond, 'fields' => 'Branch.id'));
          $o = 0;
          $branch_id = "";
          foreach ($arr as $res) {
            if ($o == 0)
              $branch_id .= "'" . $res['Branch']['id'] . "'";
            else
              $branch_id .= ",'" . $res['Branch']['id'] . "'";
            $o++;
          }
          $qs .= " AND Admin.branch_id IN (" . $branch_id . ")";
        }
        else if ($filter['field'] == 'department') {

          $data = $filter['data']['value'];
          $cond = " Department.name LIKE '%" . $data . "%'";
          $arr = $this->Department->find('all', array('conditions' => $cond, 'fields' => 'Department.id'));
          $o = 0;
          $department_id = "";
          foreach ($arr as $res) {
            if ($o == 0)
              $department_id .= "'" . $res['Department']['id'] . "'";
            else
              $department_id .= ",'" . $res['Department']['id'] . "'";
            $o++;
          }
          $qs .= " AND Admin.department_id IN (" . $department_id . ")";
        }

        else if ($filter['field'] == 'role') {

          $data = $filter['data']['value'];
          $cond = " Role.name LIKE '%" . $data . "%'";
          $arr = $this->Role->find('all', array('conditions' => $cond, 'fields' => 'Role.id'));
          $o = 0;
          $role_id = "";
          foreach ($arr as $res) {
            if ($o == 0)
              $role_id .= "'" . $res['Role']['id'] . "'";
            else
              $role_id .= ",'" . $res['Role']['id'] . "'";
            $o++;
          }
          $qs .= " AND Admin.role_id IN (" . $role_id . ")";
        }
        else if ($filter['field'] == 'name') {
          $data = $filter['data']['value'];
          $qs .= " AND (Admin.firstname LIKE '%" . $data . "%' OR Admin.lastname LIKE '%" . $data . "%')";
        } else {
          if ($encoded) {
            $field = $modelName . '.' . $filter->field;
            $value = $filter->value;
            $compare = isset($filter->comparison) ? $filter->comparison : null;
            $filterType = $filter->type;
          } else {
            $field = $modelName . '.' . $filter['field'];
            $value = $filter['data']['value'];
            $compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
            $filterType = $filter['data']['type'];
          }

          switch ($filterType) {
            case 'string' : $qs .= " AND " . $field . " LIKE '%" . $value . "%'";
              Break;
            case 'list' :
              if (strstr($value, ',')) {
                $fi = explode(',', $value);
                for ($q = 0; $q < count($fi); $q++) {
                  if ($field == $modelName . '.status') {
                    if ($fi[$q] == 'Active') {
                      $fi[$q] = 1;
                    } else {
                      $fi[$q] = 0;
                    }
                  }
                  $fi[$q] = "'" . $fi[$q] . "'";
                }
                $value = implode(',', $fi);

                $qs .= " AND " . $field . " IN (" . $value . ")";
              } else {
                if ($field == $modelName . '.status') {
                  if ($value == 'Active') {
                    $value = 1;
                  } else {
                    $value = 0;
                  }
                }
                $qs .= " AND " . $field . " = '" . $value . "'";
              }
              Break;
            case 'boolean' : $qs .= " AND " . $field . " = " . ($value);
              Break;
            case 'numeric' :
              switch ($compare) {
                case 'eq' : $qs .= " AND " . $field . " = " . $value;
                  Break;
                case 'lt' : $qs .= " AND " . $field . " < " . $value;
                  Break;
                case 'gt' : $qs .= " AND " . $field . " > " . $value;
                  Break;
              }
              Break;
            case 'date' :
              switch ($compare) {
                case 'eq' : $qs .= " AND DATE(" . $field . ") = '" . date('Y-m-d', strtotime($value)) . "'";
                  Break;
                case 'lt' : $qs .= " AND DATE(" . $field . ") < '" . date('Y-m-d', strtotime($value)) . "'";
                  Break;
                case 'gt' : $qs .= " AND DATE(" . $field . ") > '" . date('Y-m-d', strtotime($value)) . "'";
                  Break;
              }
              Break;
          }
        }
      }
      $where .= $qs;
    }
    return $where;
  }

}

?>
