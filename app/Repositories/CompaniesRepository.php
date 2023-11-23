<?php

namespace App\Repositories;

use App\Models\Companies;

class CompaniesRepository
{
    /**
     * @param array $data
     * @return array|Companies
     */
    public static function save(array $data): array|Companies
    {
        $companyModel = new Companies();
        $companyModel->name = $data['company_name'];
        $companyModel->size = $data['company_size'];
        $companyModel->address = $data['company_address'];
        $companyModel->city = $data['company_city'];
        $companyModel->country = $data['company_country'];
        $companyModel->logo = $data['logo'] ?? null;
        $companyModel->user_id = $data['user_id'];
        if (!$companyModel->save()) {
            return ['message' => 'Cannot save company'];
        }
        return $companyModel;
    }
}
