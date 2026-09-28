<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
//    public function up(): void
//    {
//        // tank_shift_logs
//        Schema::table('tank_shift_logs', function (Blueprint $table) {
//            if (Schema::hasColumn('tank_shift_logs', 'opening_totalizer')) {
//                $table->dropColumn('opening_totalizer');
//            }
//            if (Schema::hasColumn('tank_shift_logs', 'closing_totalizer')) {
//                $table->dropColumn('closing_totalizer');
//            }
//            if (Schema::hasColumn('tank_shift_logs', 'manual_opening_totalizer')) {
//                $table->dropColumn('manual_opening_totalizer');
//            }
//            if (Schema::hasColumn('tank_shift_logs', 'manual_closing_totalizer')) {
//                $table->dropColumn('manual_closing_totalizer');
//            }
//            if (!Schema::hasColumn('tank_shift_logs', 'opening_dip')) {
//                $table->bigInteger('opening_dip')->nullable()->after('user_id');
//            }
//            if (!Schema::hasColumn('tank_shift_logs', 'closing_dip')) {
//                $table->bigInteger('closing_dip')->nullable()->after('opening_dip');
//            }
//            if (!Schema::hasColumn('tank_shift_logs', 'manual_opening_dip')) {
//                $table->bigInteger('manual_opening_dip')->nullable()->after('closing_dip');
//            }
//            if (!Schema::hasColumn('tank_shift_logs', 'manual_closing_dip')) {
//                $table->bigInteger('manual_closing_dip')->nullable()->after('manual_opening_dip');
//            }
//            if (Schema::hasColumn('tank_shift_logs', 'opening_mm')) {
//                $table->renameColumn('opening_mm', 'opening_dip');
//            }
//            if (Schema::hasColumn('tank_shift_logs', 'closing_mm')) {
//                $table->renameColumn('closing_mm', 'closing_dip');
//            }
//            if (Schema::hasColumn('tank_shift_logs', 'manual_opening_mm')) {
//                $table->renameColumn('manual_opening_mm', 'manual_opening_dip');
//            }
//            if (Schema::hasColumn('tank_shift_logs', 'manual_closing_mm')) {
//                $table->renameColumn('manual_closing_mm', 'manual_closing_dip');
//            }
//        });
//
//        // tank_shifts_send
//        Schema::table('tank_shifts_send', function (Blueprint $table) {
//            if (Schema::hasColumn('tank_shifts_send', 'opening_totalizer')) {
//                $table->dropColumn('opening_totalizer');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'closing_totalizer')) {
//                $table->dropColumn('closing_totalizer');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'manual_opening_totalizer')) {
//                $table->dropColumn('manual_opening_totalizer');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'manual_closing_totalizer')) {
//                $table->dropColumn('manual_closing_totalizer');
//            }
//            if (!Schema::hasColumn('tank_shifts_send', 'opening_dip')) {
//                $table->bigInteger('opening_dip')->nullable()->after('user_id');
//            }
//            if (!Schema::hasColumn('tank_shifts_send', 'closing_dip')) {
//                $table->bigInteger('closing_dip')->nullable()->after('opening_dip');
//            }
//            if (!Schema::hasColumn('tank_shifts_send', 'manual_opening_dip')) {
//                $table->bigInteger('manual_opening_dip')->nullable()->after('closing_dip');
//            }
//            if (!Schema::hasColumn('tank_shifts_send', 'manual_closing_dip')) {
//                $table->bigInteger('manual_closing_dip')->nullable()->after('manual_opening_dip');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'opening_mm')) {
//                $table->renameColumn('opening_mm', 'opening_dip');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'closing_mm')) {
//                $table->renameColumn('closing_mm', 'closing_dip');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'manual_opening_mm')) {
//                $table->renameColumn('manual_opening_mm', 'manual_opening_dip');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'manual_closing_mm')) {
//                $table->renameColumn('manual_closing_mm', 'manual_closing_dip');
//            }
//        });
//
//        // tank_stock_event
//        Schema::table('tank_stock_event', function (Blueprint $table) {
//            if (Schema::hasColumn('tank_stock_event', 'opening_totalizer')) {
//                $table->dropColumn('opening_totalizer');
//            }
//            if (Schema::hasColumn('tank_stock_event', 'closing_totalizer')) {
//                $table->dropColumn('closing_totalizer');
//            }
//            if (Schema::hasColumn('tank_stock_event', 'manual_opening_totalizer')) {
//                $table->dropColumn('manual_opening_totalizer');
//            }
//            if (Schema::hasColumn('tank_stock_event', 'manual_closing_totalizer')) {
//                $table->dropColumn('manual_closing_totalizer');
//            }
//            if (!Schema::hasColumn('tank_stock_event', 'closing_dip')) {
//                $table->bigInteger('opening_dip')->nullable()->after('user_id');
//            }
//            if (Schema::hasColumn('tank_stock_event', 'opening_mm')) {
//                $table->renameColumn('opening_mm', 'opening_dip');
//            }
//            if (!Schema::hasColumn('tank_stock_event', 'closing_dip')) {
//                $table->bigInteger('closing_dip')->nullable();
//            }
//
//            if (Schema::hasColumn('tank_stock_event', 'closing_mm')) {
//                $table->renameColumn('closing_mm','closing_dip');
//            }
//            if (!Schema::hasColumn('tank_stock_event', 'closing_dip')) {
//                $table->bigInteger('manual_opening_mm')->nullable()->after('closing_dip');
//            }
//            if (!Schema::hasColumn('tank_stock_event', 'closing_dip')) {
//                $table->bigInteger('manual_closing_mm')->nullable()->after('manual_opening_mm');
//            }
//            if (Schema::hasColumn('tank_stock_event', 'manual_opening_mm')) {
//                $table->renameColumn('manual_opening_mm', 'manual_opening_dip');
//            }
//            if (Schema::hasColumn('tank_stock_event', 'manual_closing_mm')) {
//                $table->renameColumn('manual_closing_mm', 'manual_closing_dip');
//            }
//        });
//    }
    public function up(): void
    {
        // tank_shift_logs
        Schema::table('tank_shift_logs', function (Blueprint $table) {
            if (Schema::hasColumn('tank_shift_logs', 'opening_totalizer')) {
                $table->dropColumn('opening_totalizer');
            }
            if (Schema::hasColumn('tank_shift_logs', 'closing_totalizer')) {
                $table->dropColumn('closing_totalizer');
            }
            if (Schema::hasColumn('tank_shift_logs', 'manual_opening_totalizer')) {
                $table->dropColumn('manual_opening_totalizer');
            }
            if (Schema::hasColumn('tank_shift_logs', 'manual_closing_totalizer')) {
                $table->dropColumn('manual_closing_totalizer');
            }

            // opening_dip
            if (Schema::hasColumn('tank_shift_logs', 'opening_mm') && !Schema::hasColumn('tank_shift_logs', 'opening_dip')) {
                $table->renameColumn('opening_mm', 'opening_dip');
            } elseif (!Schema::hasColumn('tank_shift_logs', 'opening_dip')) {
                $table->bigInteger('opening_dip')->nullable()->after('user_id');
            }

            // closing_dip
            if (Schema::hasColumn('tank_shift_logs', 'closing_mm') && !Schema::hasColumn('tank_shift_logs', 'closing_dip')) {
                $table->renameColumn('closing_mm', 'closing_dip');
            } elseif (!Schema::hasColumn('tank_shift_logs', 'closing_dip')) {
                $table->bigInteger('closing_dip')->nullable()->after('opening_dip');
            }

            // manual_opening_dip
            if (Schema::hasColumn('tank_shift_logs', 'manual_opening_mm') && !Schema::hasColumn('tank_shift_logs', 'manual_opening_dip')) {
                $table->renameColumn('manual_opening_mm', 'manual_opening_dip');
            } elseif (!Schema::hasColumn('tank_shift_logs', 'manual_opening_dip')) {
                $table->bigInteger('manual_opening_dip')->nullable()->after('closing_dip');
            }

            // manual_closing_dip
            if (Schema::hasColumn('tank_shift_logs', 'manual_closing_mm') && !Schema::hasColumn('tank_shift_logs', 'manual_closing_dip')) {
                $table->renameColumn('manual_closing_mm', 'manual_closing_dip');
            } elseif (!Schema::hasColumn('tank_shift_logs', 'manual_closing_dip')) {
                $table->bigInteger('manual_closing_dip')->nullable()->after('manual_opening_dip');
            }
        });

        // tank_shifts_send
        Schema::table('tank_shifts_send', function (Blueprint $table) {
//            if (Schema::hasColumn('tank_shifts_send', 'opening_totalizer')) {
//                $table->dropColumn('opening_totalizer');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'closing_totalizer')) {
//                $table->dropColumn('closing_totalizer');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'manual_opening_totalizer')) {
//                $table->dropColumn('manual_opening_totalizer');
//            }
//            if (Schema::hasColumn('tank_shifts_send', 'manual_closing_totalizer')) {
//                $table->dropColumn('manual_closing_totalizer');
//            }

            // opening_dip
            if (Schema::hasColumn('tank_shifts_send', 'opening_mm') && !Schema::hasColumn('tank_shifts_send', 'opening_dip')) {
                $table->renameColumn('opening_mm', 'opening_dip');
            } elseif (!Schema::hasColumn('tank_shifts_send', 'opening_dip')) {
                $table->bigInteger('opening_dip')->nullable()->after('user_id');
            }

            // closing_dip
            if (Schema::hasColumn('tank_shifts_send', 'closing_mm') && !Schema::hasColumn('tank_shifts_send', 'closing_dip')) {
                $table->renameColumn('closing_mm', 'closing_dip');
            } elseif (!Schema::hasColumn('tank_shifts_send', 'closing_dip')) {
                $table->bigInteger('closing_dip')->nullable()->after('opening_dip');
            }

            // manual_opening_dip
//            if (Schema::hasColumn('tank_shifts_send', 'manual_opening_mm') && !Schema::hasColumn('tank_shifts_send', 'manual_opening_dip')) {
//                $table->renameColumn('manual_opening_mm', 'manual_opening_dip');
//            } elseif (!Schema::hasColumn('tank_shifts_send', 'manual_opening_dip')) {
//                $table->bigInteger('manual_opening_dip')->nullable()->after('closing_dip');
//            }

            // manual_closing_dip
//            if (Schema::hasColumn('tank_shifts_send', 'manual_closing_mm') && !Schema::hasColumn('tank_shifts_send', 'manual_closing_dip')) {
//                $table->renameColumn('manual_closing_mm', 'manual_closing_dip');
//            } elseif (!Schema::hasColumn('tank_shifts_send', 'manual_closing_dip')) {
//                $table->bigInteger('manual_closing_dip')->nullable()->after('manual_opening_dip');
//            }
        });

        Schema::table('tank_stock_event', function (Blueprint $table) {
            if (Schema::hasColumn('tank_stock_event', 'opening_totalizer')) {
                $table->dropColumn('opening_totalizer');
            }
            if (Schema::hasColumn('tank_stock_event', 'closing_totalizer')) {
                $table->dropColumn('closing_totalizer');
            }
            if (Schema::hasColumn('tank_stock_event', 'manual_opening_totalizer')) {
                $table->dropColumn('manual_opening_totalizer');
            }
            if (Schema::hasColumn('tank_stock_event', 'manual_closing_totalizer')) {
                $table->dropColumn('manual_closing_totalizer');
            }

            // 🔹 Opening Dip
            if (Schema::hasColumn('tank_stock_event', 'opening_mm') && !Schema::hasColumn('tank_stock_event', 'opening_dip')) {
                $table->renameColumn('opening_mm', 'opening_dip');
            } elseif (!Schema::hasColumn('tank_stock_event', 'opening_dip')) {
                $table->bigInteger('opening_dip')->nullable()->after('user_id');
            }

            // 🔹 Closing Dip (only if opening_dip exists)
            if (Schema::hasColumn('tank_stock_event', 'closing_mm') && !Schema::hasColumn('tank_stock_event', 'closing_dip')) {
                $table->renameColumn('closing_mm', 'closing_dip');
            } elseif (!Schema::hasColumn('tank_stock_event', 'closing_dip')) {
                if (Schema::hasColumn('tank_stock_event', 'opening_dip')) {
                    $table->bigInteger('closing_dip')->nullable()->after('opening_dip');
                } else {
                    $table->bigInteger('closing_dip')->nullable();
                }
            }

            // 🔹 Manual Opening Dip
            if (Schema::hasColumn('tank_stock_event', 'manual_opening_mm') && !Schema::hasColumn('tank_stock_event', 'manual_opening_dip')) {
                $table->renameColumn('manual_opening_mm', 'manual_opening_dip');
            } elseif (!Schema::hasColumn('tank_stock_event', 'manual_opening_dip')) {
                if (Schema::hasColumn('tank_stock_event', 'closing_dip')) {
                    $table->bigInteger('manual_opening_dip')->nullable()->after('closing_dip');
                } else {
                    $table->bigInteger('manual_opening_dip')->nullable();
                }
            }

            // 🔹 Manual Closing Dip
            if (Schema::hasColumn('tank_stock_event', 'manual_closing_mm') && !Schema::hasColumn('tank_stock_event', 'manual_closing_dip')) {
                $table->renameColumn('manual_closing_mm', 'manual_closing_dip');
            } elseif (!Schema::hasColumn('tank_stock_event', 'manual_closing_dip')) {
                if (Schema::hasColumn('tank_stock_event', 'manual_opening_dip')) {
                    $table->bigInteger('manual_closing_dip')->nullable()->after('manual_opening_dip');
                } else {
                    $table->bigInteger('manual_closing_dip')->nullable();
                }
            }
        });

    }


    public function down(): void
    {
        // tank_shift_logs rollback
        Schema::table('tank_shift_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('tank_shift_logs', 'opening_totalizer')) {
                $table->integer('opening_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_shift_logs', 'closing_totalizer')) {
                $table->integer('closing_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_shift_logs', 'manual_opening_totalizer')) {
                $table->integer('manual_opening_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_shift_logs', 'manual_closing_totalizer')) {
                $table->integer('manual_closing_totalizer')->nullable();
            }

            if (Schema::hasColumn('tank_shift_logs', 'opening_dip')) {
                $table->renameColumn('opening_dip', 'opening_mm');
            }
            if (Schema::hasColumn('tank_shift_logs', 'closing_dip')) {
                $table->renameColumn('closing_dip', 'closing_mm');
            }
            if (Schema::hasColumn('tank_shift_logs', 'manual_opening_dip')) {
                $table->renameColumn('manual_opening_dip', 'manual_opening_mm');
            }
            if (Schema::hasColumn('tank_shift_logs', 'manual_closing_dip')) {
                $table->renameColumn('manual_closing_dip', 'manual_closing_mm');
            }
        });

        // tank_shifts_send rollback
        Schema::table('tank_shifts_send', function (Blueprint $table) {
            if (!Schema::hasColumn('tank_shifts_send', 'opening_totalizer')) {
                $table->integer('opening_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_shifts_send', 'closing_totalizer')) {
                $table->integer('closing_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_shifts_send', 'manual_opening_totalizer')) {
                $table->integer('manual_opening_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_shifts_send', 'manual_closing_totalizer')) {
                $table->integer('manual_closing_totalizer')->nullable();
            }

            if (Schema::hasColumn('tank_shifts_send', 'opening_dip')) {
                $table->renameColumn('opening_dip', 'opening_mm');
            }
            if (Schema::hasColumn('tank_shifts_send', 'closing_dip')) {
                $table->renameColumn('closing_dip', 'closing_mm');
            }
            if (Schema::hasColumn('tank_shifts_send', 'manual_opening_dip')) {
                $table->renameColumn('manual_opening_dip', 'manual_opening_mm');
            }
            if (Schema::hasColumn('tank_shifts_send', 'manual_closing_dip')) {
                $table->renameColumn('manual_closing_dip', 'manual_closing_mm');
            }
        });

        // tank_stock_event rollback
        Schema::table('tank_stock_event', function (Blueprint $table) {
            if (!Schema::hasColumn('tank_stock_event', 'opening_totalizer')) {
                $table->integer('opening_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_stock_event', 'closing_totalizer')) {
                $table->integer('closing_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_stock_event', 'manual_opening_totalizer')) {
                $table->integer('manual_opening_totalizer')->nullable();
            }
            if (!Schema::hasColumn('tank_stock_event', 'manual_closing_totalizer')) {
                $table->integer('manual_closing_totalizer')->nullable();
            }

            if (Schema::hasColumn('tank_stock_event', 'opening_dip')) {
                $table->renameColumn('opening_dip', 'opening_mm');
            }

            if (Schema::hasColumn('tank_stock_event', 'manual_opening_dip')) {
                $table->renameColumn('manual_opening_dip', 'manual_opening_mm');
            }
            if (Schema::hasColumn('tank_stock_event', 'manual_closing_dip')) {
                $table->renameColumn('manual_closing_dip', 'manual_closing_mm');
            }
        });
    }
};
