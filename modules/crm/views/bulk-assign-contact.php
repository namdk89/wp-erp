<?php
$life_stages = erp_crm_get_life_stages_dropdown_raw();
$users       = erp_crm_get_crm_user();
?>
<form method="post" name="assign_contact_from_group" id="assign_contact_from_group">
    <div class="wrap">

        <h2><?php esc_attr_e( 'Assign selected Contacts', 'erp' ); ?></h2>

        <table class="wp-list-table widefat fixed striped contact-group-list-table contactgroups">
            <tr>
                <th><?php esc_attr_e( 'ID', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Name', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Phone', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Email', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Life Stage', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Created', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Owner', 'erp' ); ?></th>
            </tr>
            <?php
            foreach ( $_REQUEST['suscriber_contact_id'] as $id ) {
                $contact = erp_get_people_by('id', $id);
                $contact_owner = get_user_by('id', $contact->contact_owner);
                echo '<tr>
                        <td><input type="hidden" name="suscriber_contact_id[]" value="'.$id.'"/>'.$id.'</td>
                        <td>'.$contact->last_name.' '.$contact->first_name.'</td>
                        <td>'.$contact->phone.'</td>
                        <td>'.$contact->email.'</td>
                        <td>'.strtoupper($contact->life_stage).'</td>
                        <td>'.$contact->created.'</td>
                        <td>'.$contact_owner->user_email.'</td>
                      </tr>';
            }
            echo '<input type="hidden" name="filter_contact_group" value="'.$_REQUEST['filter_contact_group'].'">'
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

                <tr>
                    <th>
                        <label for="remove_from_group"><?php esc_attr_e( 'Remove from group', 'erp' ); ?></label>
                    </th>
                    <td>
                        <?php
                        if(current_user_can('erp_crm_manager')) {
                            echo '<input type="checkbox" name="remove_from_group">';
                        } else {
                            echo '<input type="checkbox" name="remove_from_group" disabled>';
                        }
                        ?>
                    </select>
                    </td>
                </tr>

            </tbody>
        </table>

        <?php wp_nonce_field( 'bulk-contactsubscribers' ); ?>
        <input type="hidden" name="action" value="assign_group_subscriber">
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Assign Contacts', 'erp' ); ?>"></p>
    </div>
</form>
<script type="text/javascript">
	var assign = document.getElementById('erp-subscriber-header');
	assign.style.display = "none";
</script>