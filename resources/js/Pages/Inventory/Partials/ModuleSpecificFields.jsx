import React from 'react';
import TextInput from '@/Components/TextInput';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';

/**
 * ModuleSpecificFields Component
 * 
 * Dynamically renders additional fields based on the shop's business type.
 * Specifically handles Pharmacy (DGDA) fields when businessType is 2.
 * 
 * @param {number|string} businessType - The business type ID (2 for Pharmacy)
 * @param {object} data - Inertia useForm data object
 * @param {function} setData - Inertia useForm setData function
 * @param {object} errors - Inertia useForm errors object
 */
export default function ModuleSpecificFields({ businessType, data, setData, errors }) {
    // Only show for Pharmacy (BusinessType::PHARMACY = 2)
    if (Number(businessType) !== 2) {
        return null;
    }

    /**
     * Helper to update specific keys inside the 'attributes' JSONB object.
     */
    const handleAttributeChange = (key, value) => {
        setData('attributes', {
            ...(data.attributes || {}),
            [key]: value
        });
    };

    return (
        <div className="space-y-4 pt-6 border-t border-gray-200 mt-6 transition-all duration-300 ease-in-out">
            <div className="flex items-center gap-2">
                <div className="p-1 bg-blue-100 rounded text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <h3 className="text-sm font-bold uppercase tracking-wider text-gray-600">
                    Pharmacy Specific Information (DGDA)
                </h3>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-1">
                    <InputLabel htmlFor="generic_name" value="Generic Name" />
                    <TextInput
                        id="generic_name"
                        type="text"
                        className="mt-1 block w-full"
                        value={data.attributes?.generic_name || ''}
                        onChange={(e) => handleAttributeChange('generic_name', e.target.value)}
                        placeholder="e.g., Paracetamol"
                    />
                    <InputError message={errors['attributes.generic_name']} className="mt-1" />
                </div>

                <div className="space-y-1">
                    <InputLabel htmlFor="dgda_code" value="DGDA Code" />
                    <TextInput
                        id="dgda_code"
                        type="text"
                        className="mt-1 block w-full"
                        value={data.attributes?.dgda_code || ''}
                        onChange={(e) => handleAttributeChange('dgda_code', e.target.value)}
                        placeholder="e.g., DGDA-P-12345"
                    />
                    <InputError message={errors['attributes.dgda_code']} className="mt-1" />
                </div>
            </div>
        </div>
    );
}
