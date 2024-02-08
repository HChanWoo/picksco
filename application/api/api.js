export default {
    // 쿠폰 사용여부 수정
    async updateIsUsed(id) {
        try {
            const response = await fetch(`update_coupon.php`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({ id: id }),
            });
            if (response.ok) {
              return 201;
            }
            if (response.status === 409) {
              return 409;
            }
            if (response.status === 500) {
              return 500;
            }
        } catch (error) {
            console.error(error);
            return 'error'
        }
    },
}