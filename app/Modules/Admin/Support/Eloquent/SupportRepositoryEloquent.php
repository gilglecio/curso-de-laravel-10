<?php

namespace App\Modules\Admin\Support\Eloquent;

use App\Modules\Admin\Support\Entity\Support;
use App\Modules\Admin\Support\Repositories\SupportRepository;

class SupportRepositoryEloquent implements SupportRepository
{
    public function getAll(string $filter = null): array
    {
        return SupportEloquent::where(function ($query) use ($filter) {
            if ($filter) {
                $query->where('subject', $filter);
                $query->orWhere('body', 'like', "%{$filter}%");
            }
        })
        ->get()
        ->toArray();
    }

    public function findOne(int $id): ?Support
    {
        $support = SupportEloquent::find($id);

        if (!$support) {
            return null;
        }

        return new Support(
            $support->subject,
            $support->status,
            $support->body,
            $support->id
        );
    }

    public function delete(int $id): void
    {
        SupportEloquent::findOrFail($id)->delete();
    }

    public function save(Support $support): int
    {
        if ($support->id) {
            $model = SupportEloquent::find($support->id);
        } else {
            $model = new SupportEloquent();
        }

        $model->subject = $support->subject;
        $model->status = $support->status;
        $model->body = $support->body;
        $model->save();

        return $model->id;
    }
}
