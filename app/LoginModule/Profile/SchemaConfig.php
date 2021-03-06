<?php

namespace App\LoginModule\Profile;

use \Illuminate\Validation\Rule;

class SchemaConfig {

    public static function login($user = null) {
        $valid = [
            'min:'.config('profile.login_validator.length.min')
        ];
        // login was changed
        if(!($user && $user->login === request()->get('login'))) {
            $valid[] = 'login';
            $valid[] = 'max:'.config('profile.login_validator.length.max');
        }
        if($user) {
            $valid[] = Rule::unique('users')->ignore($user->id);
        } else {
            $valid[] = Rule::unique('users');
        }

        return [
            'type' => 'login',
            'required' => 'required',
            'valid' => $valid
        ];
    }


    public static function first_name($user = null) {
        return [
            'type' => 'text',
            'required' => 'required',
            'valid' => 'max:100'
        ];
    }


    public static function last_name($user = null) {
        return [
            'type' => 'text',
            'required' => 'required',
            'valid' => 'max:100'
        ];
    }



    public static function graduation_grade($user = null) {
        $options = trans('graduation_grades');
        $date = \App\LoginModule\Graduation::gradeExpirationDate($user);
        return [
            'type' => 'select',
            'options' => ['' => '...'] + $options,
            'required' => 'required',
            'valid' => [
                'nullable',
                'in:'.implode(',', array_keys($options))
            ],
            'label' => trans('profile.graduation_grade_range', [
                'year_begin' => $date->year - 1,
                'year_end' => $date->year
            ]),
            'append' => [
                'graduation_year'
            ]
        ];
    }


    public static function graduation_year($user = null) {
        $year = (int) date('Y');
        return [
            'type' => 'text',
            'required' => 'required_if:graduation_grade,-2',
            'valid' => [
                'nullable',
                'integer',
                'between:'.($year - 100).','.($year + 30)
            ],
            'prepend' => [
                'graduation_grade'
            ]
        ];
    }


    public static function nationality($user = null) {
        $options = trans('countries');
        return [
            'type' => 'select',
            'options' => ['' => '...'] + $options,
            'required' => 'required',
            'valid' => 'in:,'.implode(',', array_keys($options))
        ];
    }


    public static function country_code($user = null) {
        $options = trans('countries');
        return [
            'type' => 'select',
            'options' => ['' => '...'] + $options,
            'required' => 'required',
            'valid' => 'in:,'.implode(',', array_keys($options))
        ];
    }


    public static function role($user = null) {
        $options = trans('profile.roles');
            return [
                'type' => 'select',
                'options' => $options,
                'required' => 'required',
                'valid' => 'in:'.implode(',', array_keys($options))
            ];
    }


    /*
    public static function teacher_domain_verified($user = null) {
        if($user && $user->role == 'teacher' && $user->teacher_domain_verified) {
            return [
                'type' => 'dummy'
            ];
        }
        return [
            'type' => 'teacher_domain',
            'options' => trans('profile.teacher_domain_options')
        ];
    }
    */


    public static function primary_email($user = null) {
        if($user && $user->primary_email_id) {
            $valid = Rule::unique('emails', 'email')->ignore($user->primary_email_id);
        } else {
            $valid = Rule::unique('emails', 'email');
        }
        return [
            'type' => 'email',
            'required' => $user && is_null($user->creator_client_id) ? ['required', 'email'] : ['email'],
            'valid' => [$valid]
        ];
    }


    /*
    public static function primary_email_verified($user = null) {
        if($user && $user->primary_email_id && $user->primary_email_verified) {
            return [
                'type' => 'message_success',
                'label' => trans('profile.email_verified')
            ];
        }
        return [
            'type' => 'text',
            'name' => 'primary_email_verification_code',
            'help' => trans('profile.email_verification_help', [
                'email' => '<a href="mailto:'.config('mail.from.address').'">'.config('mail.from.address').'</a>'
            ])
        ];
    }
    */


    public static function secondary_email($user = null) {
        if($user && $user->secondary_email_id) {
            $valid = Rule::unique('emails', 'email')->ignore($user->secondary_email_id);
        } else {
            $valid = Rule::unique('emails', 'email');
        }

        return [
            'type' => 'email',
            'required' => $user && is_null($user->creator_client_id) ? ['required', 'email'] : ['email'],
            'valid' => [
                'value_different:primary_email',
                $valid
            ]
        ];
    }


    /*
    public static function secondary_email_verified($user = null) {
        if($user && $user->secondary_email_id && $user->secondary_email_verified) {
            return [
                'type' => 'message_success',
                'label' => trans('profile.email_verified')
            ];
        }
        return [
            'type' => 'text',
            'name' => 'secondary_email_verification_code',
            'help' => trans('profile.email_verification_help', [
                'email' => '<a href="mailto:'.config('mail.from.address').'">'.config('mail.from.address').'</a>'
            ])
        ];
    }
    */


    public static function language($user = null) {
        $options = config('app.locales');
        return [
            'type' => 'select',
            'options' => $options,
            'required' => 'required',
            'valid' => 'in:'.implode(',', array_keys($options)),
        ];
    }





    public static function address($user = null) {
        return [
            'type' => 'text',
            'required' => 'required',
            'valid' => 'max:255'
        ];
    }


    public static function city($user = null) {
        return [
            'type' => 'text',
            'required' => 'required',
            'valid' => 'max:255'
        ];
    }


    public static function zipcode($user = null) {
        return [
            'type' => 'text',
            'required' => 'required',
            'valid' => 'max:20'
        ];
    }


    public static function timezone($user = null) {
        $options = trans('timezones');
        return [
            'type' => 'select',
            'options' => ['' => '...'] + $options,
            'required' => 'required',
            'valid' => 'in:,'.implode(',', array_keys($options))
        ];
    }


    public static function primary_phone($user = null) {
        return [
            'type' => 'text',
            'required' => 'required',
            'valid' => 'max:255'
        ];
    }


    public static function secondary_phone($user = null) {
        return [
            'type' => 'text',
            'required' => 'required',
            'valid' => 'max:255'
        ];
    }

    public static function subscription_results($user = null) {
        return [
            'type' => 'checkbox'
        ];
    }

    public static function subscription_news($user = null) {
        return [
            'type' => 'checkbox'
        ];
    }


    public static function birthday($user = null) {
        return [
            'type' => 'date',
            'required' => 'required',
            'valid' => [
                'nullable',
                'date_format:"Y-m-d"',
                'before:today'
            ]
        ];
    }


    public static function birthday_year($user = null) {
        $year = (int) date('Y');
        return [
            'type' => 'text',
            'required' => 'required',
            'valid' => [
                'nullable',
                'integer',
                'between:'.($year - 100).','.$year
            ]
        ];
    }


    public static function gender($user = null) {
        $options = trans('profile.genders');
        return [
            'type' => 'radios',
            'options' => $options,
            'required' => 'required',
            'valid' => 'in:'.implode(',', array_keys($options))
        ];
    }


    public static function presentation($user = null) {
        return [
            'type' => 'textarea',
            'required' => 'required'
        ];
    }


    public static function website($user = null) {
        return [
            'type' => 'text',
            'required' => 'required'
        ];
    }



    /*
    Disabled temporarily
    public static function ministry_of_education($user = null) {
        $country_codes = array_keys(trans('countries'));
        return [
            'type' => 'checkbox',
            'required' => 'required_if:role,teacher|required_if:country_code,'.implode(',', array_diff($country_codes, ['fr']))
        ];
    }


    public static function ministry_of_education_fr($user = null) {
        return [
            'type' => 'checkbox'
        ];
    }


    public static function school_grade($user = null) {
        return [
            'type' => 'text',
            'required' => 'required'
        ];
    }
*/

    public static function student_id($user = null) {
        return [
            'type' => 'text',
            'required' => 'required'
        ];
    }


    public static function picture($user = null) {
        return [
            'type' => 'picture',
            'valid' => [
                'image',
                'max:'.(1024*config('ui.profile_picture.max_file_size'))
            ]
        ];
    }


    public static function public_info($user) {
        return [
            'type' => 'public_info'
        ];
    }


    public static function real_name_visible($user = null) {
        return [
            'type' => 'checkbox'
        ];
    }

    public static function public_name($user = null) {
        return [
            'type' => 'public_name'
        ];
    }

}