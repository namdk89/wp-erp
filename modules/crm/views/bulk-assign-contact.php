<?php
$life_stages = erp_crm_get_life_stages_dropdown_raw();
$users       = erp_crm_get_crm_user();
?>
<form method="post" name="assign_contcontact_from_useract_from_group" id="assign_contact_from_group">
    <div class="wrap">

        <h2><?php esc_attr_e( 'Assign selected Contacts', 'erp' ); ?></h2>

        <table class='wp-list-table widefat fixed striped contact-group-list-table contactgroups'>
            <tr>
                <th><?php esc_attr_e( 'Index', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Name', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Phone', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Email', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Created', 'erp' ); ?></th>
            </tr>
            <?php
            $index = 1;
            foreach ( $_REQUEST['suscriber_contact_id'] as $id ) {
                $contact = erp_get_people_by('id', $id);
                echo '<tr>
                        <td>'.$index.'</td>
                        <td>'.$contact->last_name.' '.$contact->first_name.'</td>
                        <td>'.$contact->phone.'</td>
                        <td>'.$contact->email.'</td>
                        <td>'.$contact->created.'</td>
                      </tr>';
                $index ++;
            }
            ?>
        </table>

        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label for="contact_owner"><?php esc_attr_e( 'Contact Owner', 'erp' ); ?></label>
                    </th>
                    <td>
                        <select name="contact_owner" id="contact_owner" class="">
                            <option value=""><?php esc_attr_e( '&mdash; Select Owner &mdash;', 'erp' ); ?></option>
                            <?php
                            foreach ( $users as $user ) {
                                echo '<option value="' . $user->ID . '">' . $user->display_name . ' &lt;' . $user->user_email . '&gt;' . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="life_stage"><?php esc_attr_e( 'Life Stage', 'erp' ); ?></label>
                    </th>
                    <td>
                        <select name="life_stage" id="life_stage">
                        <?php
                        echo '<option value="">' . "--Select--" . '</option>';
                        foreach ( $life_stages as $key => $value ) {
                            echo '<option value="' . $key . '">' . $value . '</option>';
                        }
                        ?>
                    </select>
                    </td>
                </tr>

            </tbody>
        </table>

        <input type="hidden" name="action" value="assign_group_subscriber">
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Assign Contacts', 'erp' ); ?>"></p>
    </div>
</form>
<script type="text/javascript">
	var assign = document.getElementById('erp-subscriber-header');
	assign.style.display = "none";
</script>