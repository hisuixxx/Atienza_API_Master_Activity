<?php

namespace App\Permissions\V1;

use app\Models\User;  

final class Abilities
    {
        // Booking abilities
        public const CreateBooking = 'booking:create';
        public const CreateOwnBooking = 'booking:own:create';
        public const UpdateBooking = 'booking:update';
        public const UpdateOwnBooking = 'booking:own:update';
        public const ReplaceBooking = 'booking:replace';
        public const DeleteBooking = 'booking:delete';
        public const DeleteOwnBooking = 'booking:own:delete';

        // User management abilities (managers only)
        public const CreateUser = 'user:create';
        public const UpdateUser = 'user:update';
        public const ReplaceUser = 'user:replace';
        public const DeleteUser = 'user:delete';

        /**
         * Returns the list of abilities a user receives. The list is written into the
         * JWT at login, so the policies can read it without querying the database.
         */
        public static function getAbilities(User $user): array
        {
            // Managers may act on anything
            if ($user->is_manager) {
                return [
                    self::CreateBooking,
                    self::UpdateBooking,
                    self::ReplaceBooking,
                    self::DeleteBooking,
                    self::CreateUser,
                    self::UpdateUser,
                    self::ReplaceUser,
                    self::DeleteUser,
                ];
            }

            // Regular users may only act on their own bookings
            return [
                self::CreateOwnBooking,
                self::UpdateOwnBooking,
                self::DeleteOwnBooking,
            ];
        }
    
    
    
        
    }     