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

    /**
     * @param Companies $company
     * @param array $data
     * @return array|Companies
     */
    public static function update(Companies $company, array $data): array|Companies
    {
        $company->name = $data['company_name'];
        $company->size = $data['company_size'];
        $company->address = $data['company_address'];
        $company->city = $data['company_city'];
        $company->country = $data['company_country'];
        $company->logo = $data['company_logo'] ?? null;
        if (!$company->save()) {
            return ['message' => 'Cannot update company'];
        }
        return $company;
    }

    /**
     * @param integer $companyId
     * @return array|Companies
     */
    public static function get(int $companyId): array|Companies
    {
        $companyData = Companies::select('id', 'name', 'size', 'address', 'city', 'country', 'logo')->where('id', $companyId)->first();
        if (!$companyData instanceof Companies) {
            return ['message' => 'Cannot find company'];
        }
        return $companyData;
    }
}
