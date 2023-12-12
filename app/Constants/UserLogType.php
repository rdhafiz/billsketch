<?php

namespace App\Constants;

class UserLogType
{
    const Register = 'Register';
    const Account_Activation = 'Account Activation';
    const Login = 'Login';
    const SocialLoginFb = 'Social Login Facebook';
    const SocialLoginGl = 'Social Login Google';
    const Logout = 'Logout';
    const Update_profile = 'Update profile';
    const Reset_password = 'Reset password';
    const Change_password = 'Change password';
    const Client_create = 'Client create';
    const Client_view = 'Client view';
    const Client_update = 'Client update';
    const Client_delete = 'Client delete';
    const Client_archive = 'Client archive';
    const Client_restore = 'Client restore';
    const Employee_create = 'Employee create';
    const Employee_view = 'Employee view';
    const Employee_update = 'Employee update';
    const Employee_delete = 'Employee delete';
    const Employee_archive = 'Employee archive';
    const Employee_restore = 'Employee restore';
    const Category_create = 'Category create';
    const Category_view = 'Category view';
    const Category_update = 'Category update';
    const Category_delete = 'Category delete';
    const Category_archive = 'Category archive';
    const Category_restore = 'Category restore';
    const Invoice_create = 'Invoice create';
    const Recurring_Invoice_create = 'Recurring Invoice create';
    const Invoice_view = 'Invoice view';
    const Invoice_update = 'Invoice update';
    const Recurring_Invoice_update = 'Recurring Invoice update';
    const Invoice_delete = 'Invoice delete';
    const Recurring_Invoice_delete = 'Recurring Invoice delete';
    const Invoice_archive = 'Invoice archive';
    const Invoice_restore = 'Invoice restore';
    const Share_invoice = 'Share invoice';
    const Download_invoice = 'Download invoice';
    const Qrcode_invoice = 'Qrcode invoice';
    const Invoice_item_create = 'Invoice item create';
    const Invoice_item_view = 'Invoice item view';
    const Invoice_item_update = 'Invoice item update';
    const Invoice_item_delete = 'Invoice item delete';

    public static function getArray()
    {
        return [
            ['value' => self::Register, 'name'=> self::Register],
            ['value' => self::Account_Activation, 'name'=> self::Account_Activation],
            ['value' => self::Login, 'name'=> self::Login],
            ['value' => self::SocialLoginFb, 'name'=> self::SocialLoginFb],
            ['value' => self::SocialLoginGl, 'name'=> self::SocialLoginGl],
            ['value' => self::Logout, 'name'=> self::Logout],
            ['value' => self::Update_profile, 'name'=> self::Update_profile],
            ['value' => self::Reset_password, 'name'=> self::Reset_password],
            ['value' => self::Change_password, 'name'=> self::Change_password],
            ['value' => self::Client_create, 'name'=> self::Client_create],
            ['value' => self::Client_view, 'name'=> self::Client_view],
            ['value' => self::Client_update, 'name'=> self::Client_update],
            ['value' => self::Client_delete, 'name'=> self::Client_delete],
            ['value' => self::Client_archive, 'name'=> self::Client_archive],
            ['value' => self::Client_restore, 'name'=> self::Client_restore],
            ['value' => self::Employee_create, 'name'=> self::Employee_create],
            ['value' => self::Employee_view, 'name'=> self::Employee_view],
            ['value' => self::Employee_update, 'name'=> self::Employee_update],
            ['value' => self::Employee_delete, 'name'=> self::Employee_delete],
            ['value' => self::Employee_archive, 'name'=> self::Employee_archive],
            ['value' => self::Employee_restore, 'name'=> self::Employee_restore],
            ['value' => self::Category_create, 'name'=> self::Category_create],
            ['value' => self::Category_view, 'name'=> self::Category_view],
            ['value' => self::Category_update, 'name'=> self::Category_update],
            ['value' => self::Category_delete, 'name'=> self::Category_delete],
            ['value' => self::Category_archive, 'name'=> self::Category_archive],
            ['value' => self::Category_restore, 'name'=> self::Category_restore],
            ['value' => self::Invoice_create, 'name'=> self::Invoice_create],
            ['value' => self::Invoice_view, 'name'=> self::Invoice_view],
            ['value' => self::Invoice_update, 'name'=> self::Invoice_update],
            ['value' => self::Invoice_delete, 'name'=> self::Invoice_delete],
            ['value' => self::Invoice_archive, 'name'=> self::Invoice_archive],
            ['value' => self::Invoice_restore, 'name'=> self::Invoice_restore],
            ['value' => self::Share_invoice, 'name'=> self::Share_invoice],
            ['value' => self::Download_invoice, 'name'=> self::Download_invoice],
            ['value' => self::Qrcode_invoice, 'name'=> self::Qrcode_invoice],
            ['value' => self::Invoice_item_create, 'name'=> self::Invoice_item_create],
            ['value' => self::Invoice_item_view, 'name'=> self::Invoice_item_view],
            ['value' => self::Invoice_item_update, 'name'=> self::Invoice_item_update],
            ['value' => self::Invoice_item_delete, 'name'=> self::Invoice_item_delete],
        ];
    }
}
