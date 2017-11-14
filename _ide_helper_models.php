<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Location
 *
 * @property int $id
 * @property int $user_id
 * @property string $address
 * @property string $zip_code
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereZipCode($value)
 */
	class Location extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $account
 * @property string $password
 * @property string|null $sn
 * @property string $role
 * @property string $name
 * @property string|null $sign_up_datatime
 * @property string $birthday
 * @property string $gender
 * @property string $email
 * @property int $enable
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSignUpDatatime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSn($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Verification
 *
 * @property int $id
 * @property int $user_id
 * @property string $front_picture
 * @property string $back_picture
 * @property string|null $upload_datetime
 * @property string|null $verify_result
 * @property string|null $datetime
 * @property string|null $description
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Verification whereBackPicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Verification whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Verification whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Verification whereFrontPicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Verification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Verification whereUploadDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Verification whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Verification whereVerifyResult($value)
 */
	class Verification extends \Eloquent {}
}

