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
        $companyModel->company_name = $data['company_name'];
        $companyModel->company_size = $data['company_size'];
        $companyModel->company_address = $data['company_address'];
        $companyModel->company_city = $data['company_city'];
        $companyModel->company_country = $data['company_country'];
        $companyModel->logo = $data['logo'] ?? null;
        $companyModel->user_id = $data['user_id'];
        if (!$companyModel->save()) {
            return ['message' => 'Cannot save company'];
        }
        return $companyModel;
    }
}
