<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Pages</h4>
        </div>
        <div class="content table-responsive table-full-width">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('store_id') ?></th>
                <th scope="col">Módulos feitos</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
                <?php if($user->course_progress != null && count($user->course_progress)==2 && $user->active): ?>
                    <tr>
                        <td><?=$user->id;?></td>
                        <td><?=$user->name;?></td>
                        <td><?=$user->store_id;?></td>
                        <td>
                            <?php foreach ($user->course_progress as $cp): ?>
                                <?=$cp->course_id;?> | 
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php 
                            foreach ($user->course_progress as $cp):
                                switch($cp->course_id):
                                    case 4:
                                        // $date = $cp->created->year.'-'.$cp->created->month.'-'.$cp->created->day;
                                        echo $cp->created;
                                        // echo strtotime($date)<strtotime('01-12-2018')?'s':'n';
                                    break;
                                endswitch;    
                            endforeach;
                            ?>
                        </td>
                    </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
