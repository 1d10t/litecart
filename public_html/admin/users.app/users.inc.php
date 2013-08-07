<?php
  if (!isset($_GET['page'])) $_GET['page'] = 1;
  
  if (!empty($_POST['enable']) || !empty($_POST['disable'])) {
  
    if (!empty($_POST['users'])) {
    
      foreach ($_POST['users'] as $user_id) {
      
        $user = new ctrl_user($user_id);
        $user->data['status'] = !empty($_POST['enable']) ? 1 : 0;
        $user->save();
      }
      
      header('Location: '. $system->document->link());
      exit;
    }
  }
?>
<div style="float: right;"><?php echo $system->functions->form_draw_link_button($system->document->link('', array('doc' => 'edit_user'), true), $system->language->translate('title_create_new_user', 'Create New User'), '', 'add'); ?></div>
<h1 style="margin-top: 0px;"><img src="<?php echo WS_DIR_ADMIN . $_GET['app'] .'.app/icon.png'; ?>" width="32" height="32" style="vertical-align: middle; margin-right: 10px;" /><?php echo $system->language->translate('title_users', 'Users'); ?></h1>

<?php echo $system->functions->form_draw_form_begin('users_form', 'post'); ?>
<table width="100%" align="center" class="dataTable">
  <tr class="header">
    <th><?php echo $system->functions->form_draw_checkbox('checkbox_toggle', '', ''); ?></th>
    <th nowrap="nowrap" align="left" style="width: 100%;"><?php echo $system->language->translate('title_username', 'Username'); ?></th>
    <th>&nbsp;</th>
  </tr>
<?php
  $users_query = $system->database->query(
    "select * from ". DB_TABLE_USERS ."
    order by username;"
  );
  
  if ($system->database->num_rows($users_query) > 0) {
  
  // Jump to data for current page
    if ($_GET['page'] > 1) $system->database->seek($users_query, ($system->settings->get('data_table_rows_per_page') * ($_GET['page']-1)));
  
    $page_items = 0;
    while ($user = $system->database->fetch($users_query)) {
      if (!isset($rowclass) || $rowclass == 'even') {
        $rowclass = 'odd';
      } else {
        $rowclass = 'even';
      }
?>
  <tr class="<?php echo $rowclass; ?>"<?php echo empty($user['status']) ? ' style="color: #999;"' : ''; ?>>
    <td align="left" nowrap="nowrap"><img src="<?php echo WS_DIR_IMAGES .'icons/16x16/'. (!empty($user['status']) ? 'on.png' : 'off.png') ?>" width="16" height="16" align="absbottom" /> <?php echo $system->functions->form_draw_checkbox('users['. $user['id'] .']', $user['id']); ?></td>
    <td align="left" nowrap="nowrap"><?php echo $user['username']; ?></td>
    <td align="right" nowrap="nowrap"><a href="<?php echo $system->document->href_link('', array('doc' => 'edit_user', 'user_id' => $user['id']), true); ?>"><img src="<?php echo WS_DIR_IMAGES . 'icons/16x16/edit.png'; ?>" width="16" height="16" alt="<?php echo $system->language->translate('title_edit', 'Edit'); ?>" title="<?php echo $system->language->translate('title_edit', 'Edit'); ?>" /></a></td>
  </tr>
<?php
      if (++$page_items == $system->settings->get('data_table_rows_per_page')) break;
    }
  }
?>
  <tr class="footer">
    <td colspan="3" align="left"><?php echo $system->language->translate('title_users', 'Users'); ?>: <?php echo $system->database->num_rows($users_query); ?></td>
  </tr>
</table>

<script type="text/javascript">
  $(".dataTable input[name='checkbox_toggle']").click(function() {
    $(this).closest("form").find(":checkbox").each(function() {
      $(this).attr('checked', !$(this).attr('checked'));
    });
    $(".dataTable input[name='checkbox_toggle']").attr("checked", true);
  });

  $('.dataTable tr').click(function(event) {
    if ($(event.target).is('input:checkbox')) return;
    if ($(event.target).is('a, a *')) return;
    if ($(event.target).is('th')) return;
    $(this).find('input:checkbox').trigger('click');
  });
</script>

<p><?php echo $system->functions->form_draw_button('enable', $system->language->translate('title_enable', 'Enable'), 'submit', '', 'on'); ?> <?php echo $system->functions->form_draw_button('disable', $system->language->translate('title_disable', 'Disable'), 'submit', '', 'off'); ?></p>

<?php
  echo $system->functions->form_draw_form_end();
  
// Display page links
  echo $system->functions->draw_pagination(ceil($system->database->num_rows($users_query)/$system->settings->get('data_table_rows_per_page')));
  
?>